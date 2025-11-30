<?php

namespace App\Http\Controllers\Admin;

use App\Models\Apar;
use App\Models\Zone;
use App\Models\Brand;
use App\Models\Floor;
use App\Models\AparType;
use App\Models\Building;
use App\Exports\AparExport;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\Image;
use SweetAlert2\Laravel\Swal;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\ExtinguisherCondition;
use Illuminate\Support\Facades\Response;
use Yajra\DataTables\Facades\DataTables;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Http\Requests\Apar\StoreAparRequest;
use App\Http\Requests\Apar\UpdateAparRequest;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class AparController extends Controller implements HasMiddleware
{
    /**
     * role & permission
     */
    public static function middleware()
    {
        return [
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('apar.view'), only:['index', 'getAparData', 'show', 'downloadQrCodeSvg']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('apar.create'), only:['create', 'store']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('apar.update'), only:['update', 'edit']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('apar.destroy'), only:['destroy']),
        ];
    }
    
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->getAparData($request);
        }

        // Get filter data
        $zones = Zone::orderBy('name')->get();
        $buildings = Building::orderBy('name')->get();
        $floors = Floor::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        $aparTypes = AparType::orderBy('name')->get();
        $conditions = ExtinguisherCondition::orderBy('name')->get();

        return view('admin.apar.index', compact(
            'zones', 
            'buildings', 
            'floors', 
            'brands', 
            'aparTypes', 
            'conditions'
        ));
    }

    private function getAparData(Request $request)
    {
        $query = Apar::with([
            'zone', 
            'building', 
            'floor', 
            'brand', 
            'aparType', 
            'extinguisherCondition',
            'user'
        ]);

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('number_apar', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhereHas('zone', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('building', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Apply filters
        if ($request->has('zone_id') && !empty($request->zone_id)) {
            $query->where('zone_id', $request->zone_id);
        }

        if ($request->has('building_id') && !empty($request->building_id)) {
            $query->where('building_id', $request->building_id);
        }

        if ($request->has('floor_id') && !empty($request->floor_id)) {
            $query->where('floor_id', $request->floor_id);
        }

        if ($request->has('brand_id') && !empty($request->brand_id)) {
            $query->where('brand_id', $request->brand_id);
        }

        if ($request->has('apar_type_id') && !empty($request->apar_type_id)) {
            $query->where('apar_type_id', $request->apar_type_id);
        }

        if ($request->has('condition_id') && !empty($request->condition_id)) {
            $query->where('extinguisher_condition_id', $request->condition_id);
        }

        if ($request->has('status') && $request->status !== '') {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if ($request->has('expired_status') && !empty($request->expired_status)) {
            $today = now()->format('Y-m-d');
            if ($request->expired_status === 'expired') {
                $query->where('expired_date', '<', $today);
            } elseif ($request->expired_status === 'not_expired') {
                $query->where('expired_date', '>=', $today);
            } elseif ($request->expired_status === 'no_date') {
                $query->whereNull('expired_date');
            }
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('status_badge', function($apar) {
                if ($apar->is_active) {
                    return '<span class="badge bg-success">Aktif</span>';
                } else {
                    return '<span class="badge bg-danger">Non-Aktif</span>';
                }
            })
            ->addColumn('expired_status', function($apar) {
                if (!$apar->expired_date) {
                    return '<span class="badge bg-secondary">Tidak Ada Tanggal</span>';
                }
                
                $today = now();
                $expiredDate = \Carbon\Carbon::parse($apar->expired_date);
                
                if ($expiredDate->lessThan($today)) {
                    return '<span class="badge bg-danger">Kadaluarsa</span>';
                } elseif ($expiredDate->diffInDays($today) <= 30) {
                    return '<span class="badge bg-warning">Akan Kadaluarsa</span>';
                } else {
                    return '<span class="badge bg-success">Aman</span>';
                }
            })
            ->addColumn('formatted_expired_date', function($apar) {
                if ($apar->expired_date) {
                    return \Carbon\Carbon::parse($apar->expired_date)->format('d/m/Y');
                }
                return '-';
            })
            ->addColumn('formatted_weight', function($apar) {
                return $apar->weight_of_extinguiser . ' kg';
            })
            ->addColumn('action', function($apar) {
                return '
                    <div style="display: inline-flex; align-items: center; gap: 6px;">
                        <a href="'.route('apar.show', $apar->id).'" class="btn btn-light btn-sm" style="border: 1px solid rgba(0,0,0,0.15)" title="Lihat">
                            <i class="fas fa-eye text-primary"></i>
                        </a>
                        <a href="'.route('apar.edit', $apar->id).'" class="btn btn-light btn-sm" style="border: 1px solid rgba(0,0,0,0.15)" title="Edit">
                            <i class="fas fa-edit "></i>
                        </a>
                        <form action="'.route('apar.destroy', $apar->id).'" method="POST" id="delete-form-'.$apar->id.'" style="display:inline;">
                            '.csrf_field().'
                            '.method_field('DELETE').'
                            <button type="button" class="btn btn-light btn-sm" style="border: 1px solid rgba(0,0,0,0.15)" title="Hapus" onclick="confirmDelete('.$apar->id.')">
                                <i class="fas fa-trash text-danger"></i>
                            </button>
                        </form>
                    </div>
                ';
            })
            
            
            ->addColumn('qrcode_display', function ($apar) {
                if ($apar->qr_code) {
                    return '
                        <div class="text-center" style="max-width: 80px;">
                            ' . $apar->qr_code_small . '
                            <div class="mt-1">
                                <a href="' . route('apar.download-qrcode-svg', $apar->id) . '" 
                                   class="btn btn-sm btn-outline-secondary w-100" 
                                   style="font-size: 0.7rem; padding: 0.2rem 0.3rem;"
                                   title="Download SVG">
                                    <i class="fa fa-download"></i> SVG
                                </a>
                            </div>
                        </div>
                    ';
                }
                return '<span class="text-muted">-</span>';
            })
            ->rawColumns(['status_badge', 'expired_status', 'action', 'qrcode_display'])
            ->filter(function($query) use ($request) {
                // Global search
                if ($request->has('search') && !empty($request->search['value'])) {
                    $search = $request->search['value'];
                    $query->where(function($q) use ($search) {
                        $q->where('number_apar', 'like', '%'.$search.'%')
                          ->orWhere('location', 'like', '%'.$search.'%')
                          ->orWhere('description', 'like', '%'.$search.'%')
                          ->orWhere('qr_code', 'like', '%'.$search.'%')
                          ->orWhereHas('zone', function($q) use ($search) {
                              $q->where('name', 'like', '%'.$search.'%');
                          })
                          ->orWhereHas('building', function($q) use ($search) {
                              $q->where('name', 'like', '%'.$search.'%');
                          })
                          ->orWhereHas('floor', function($q) use ($search) {
                              $q->where('name', 'like', '%'.$search.'%');
                          })
                          ->orWhereHas('brand', function($q) use ($search) {
                              $q->where('name', 'like', '%'.$search.'%');
                          })
                          ->orWhereHas('aparType', function($q) use ($search) {
                              $q->where('name', 'like', '%'.$search.'%');
                          })
                          ->orWhereHas('extinguisherCondition', function($q) use ($search) {
                              $q->where('name', 'like', '%'.$search.'%');
                          });
                    });
                }
            })
            ->toJson();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $zones = Zone::all();
        $buildings = Building::all();
        $floors = Floor::all();
        $brands = Brand::all();
        $aparTypes = AparType::all();
        $extinguisherConditions = ExtinguisherCondition::all();
        $lastApar = Apar::orderBy('created_at', 'desc')->first();
        return view('admin.apar.create', compact('zones', 'buildings', 'floors', 'brands', 'aparTypes', 'extinguisherConditions', 'lastApar'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAparRequest $request)
    {
        try {
            $user = Auth::user();

            $validated = $request->validated();

            $validated['user_id'] = $user->id;
            $validated['updated_by'] = $user->id;

            Apar::create($validated);

            Swal::success([
                'title' => 'Data berhasil disimpan',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('apar.index');
        } catch (\Exception $e) {
            Log::error('Data APAR gagal disimpan : ' . $e->getMessage());

            Swal::error([
                'title' => 'Data gagal disimpan',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Apar $apar)
    {

        // dd($apar);
        $zones = Zone::all();
        $buildings = Building::all();
        $floors = Floor::all();
        $brands = Brand::all();
        $aparTypes = AparType::all();
        $extinguisherConditions = ExtinguisherCondition::all();
        return view('admin.apar.show', compact('apar', 'zones', 'buildings', 'floors', 'brands', 'aparTypes', 'extinguisherConditions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Apar $apar)
    {
        $zones = Zone::all();
        $buildings = Building::all();
        $floors = Floor::all();
        $brands = Brand::all();
        $aparTypes = AparType::all();
        $extinguisherConditions = ExtinguisherCondition::all();
        return view('admin.apar.edit', compact('apar', 'zones', 'buildings', 'floors', 'brands', 'aparTypes', 'extinguisherConditions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAparRequest $request, Apar $apar)
    {
        try {
            $user = Auth::user();

            $validated = $request->validated();

            $validated['updated_by'] = $user->id;

            $apar->update($validated);

            Swal::success([
                'title' => 'Data berhasil diperbarui',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('apar.index');
        } catch (\Exception $e) {
            Log::error('Data APAR gagal diperbarui : ' . $e->getMessage());

            Swal::error([
                'title' => 'Data gagal diperbarui',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Apar $apar)
    {
        try {
            $apar->delete();

            Swal::success([
                'title' => 'Data berhasil dihapus',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('apar.index');
        } catch (\Exception $e) {
            Log::error('Data gagal dihapus : ' . $e->getMessage());

            Swal::error([
                'title' => 'Data gagal dihapus',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->back();
        }
    }

    /**
     * Download QR Code sebagai SVG
     */
    public function downloadQrCodeSvg(Apar $apar)
    {
        if (!$apar->qr_code) {
            return redirect()->back()->with('error', 'QR Code tidak tersedia');
        }

        $qrCode = QrCode::size(400)
            ->color(0, 0, 0)
            ->backgroundColor(255, 255, 255)
            ->generate($apar->getQrCodeContent());
        
        $filename = 'qrcode-apar-' . $apar->number_apar . '.svg';
        
        return response($qrCode)
            ->header('Content-type', 'image/svg+xml')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    // ========================= end qr code

}
