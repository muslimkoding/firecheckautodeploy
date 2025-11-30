<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Hydrant\StoreHydrantRequest;
use App\Http\Requests\Hydrant\UpdateHydrantRequest;
use App\Models\Brand;
use App\Models\Building;
use App\Models\ExtinguisherCondition;
use App\Models\Floor;
use App\Models\Hydrant;
use App\Models\HydrantType;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Spatie\Permission\Middleware\PermissionMiddleware;
use SweetAlert2\Laravel\Swal;
use Yajra\DataTables\Facades\DataTables;

class HydrantController extends Controller implements HasMiddleware
{
    /**
     * role & permission
     */
    public static function middleware()
    {
        return [
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('hydrant.view'), only:['index', 'getHydrantData', 'show', 'downloadQrCodeSvg']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('hydrant.create'), only:['create', 'store']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('hydrant.update'), only:['update', 'edit']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('hydrant.destroy'), only:['destroy']),
        ];
    }
    
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->getHydrantData($request);
        }

        // Get filter data
        $zones = Zone::orderBy('name')->get();
        $buildings = Building::orderBy('name')->get();
        $floors = Floor::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        $hydrantType = HydrantType::orderBy('name')->get();
        $conditions = ExtinguisherCondition::orderBy('name')->get();

        return view('admin.hydrant.index', compact(
            'zones',
            'buildings',
            'floors',
            'brands',
            'hydrantType',
            'conditions',
        ));
    }

    private function getHydrantData(Request $request)
    {
        $query = Hydrant::with([
            'zone',
            'building',
            'floor',
            'brand',
            'hydrantType',
            'extinguisherCondition',
            'user'
        ]);

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('number_hydrant', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhereHas('zone', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('building', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // apply filters
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

        if ($request->has('hydrant_type_id') && !empty($request->hydrant_type_id)) {
            $query->where('hydrant_type_id', $request->hydrant_type_id);
        }

        if ($request->has('condition_id') && !empty($request->condition_id)) {
            $query->where('extinguisher_condition_id', $request->condition_id);
        }

        if ($request->has('status') && $request->status !== '') {
            if ($request->status == 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('status_badge', function($hydrant) {
                if ($hydrant->is_active) {
                    return '<span class="badge badge bg-success">Aktif</span>';
                } else {
                    return '<span class="badge bg-danger">Non-Aktif</span>';
                }
            })
            ->addColumn('action', function($hydrant) {
                return '
                    <div style="display: inline-flex; align-items: center; gap: 6px;">
                        <a href="'.route('hydrant.show', $hydrant->id).'" class="btn btn-light btn-sm" style="border: 1px solid rgba(0,0,0,0.15)" title="Lihat">
                            <i class="fas fa-eye text-primary"></i>
                        </a>
                        <a href="'.route('hydrant.edit', $hydrant->id).'" class="btn btn-light btn-sm" style="border: 1px solid rgba(0,0,0,0.15)" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="'.route('hydrant.destroy', $hydrant->id).'" method="POST" id="delete-form-'.$hydrant->id.'" style="display:inline;">
                            '.csrf_field().'
                            '.method_field('DELETE').'
                            <button type="button" class="btn btn-light btn-sm" style="border: 1px solid rgba(0,0,0,0.15)" title="Hapus" onclick="confirmDelete('.$hydrant->id.')">
                                <i class="fas fa-trash text-danger"></i>
                            </button>
                        </form>
                    </div>
                ';
            })
            ->addColumn('qrcode_display', function($hydrant) {
                if ($hydrant->qr_code) {
                    return '
                        <div class="text-center" style="max-width: 80px;">
                            ' . $hydrant->qr_code_small . '
                            <div class="mt-1">
                                <a href="' . route('hydrant.download-qrcode-svg', $hydrant->id) . '" 
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
            ->rawColumns(['status_badge', 'action', 'qrcode_display'])
            ->filter(function($query) use ($request) {
                // global search
                if ($request->has('search') && !empty($request->search['value'])) {
                    $search = $request->search['value'];
                    $query->where(function($q) use ($search) {
                        $q->where('number_hydrant', 'like', '%'.$search.'%')
                        ->orWhere('location', 'like', '%'.$search.'%')
                        ->orWhere('description', 'like', '%'.$search.'%')
                        ->orWhere('qr_code', 'like', '%'.$search.'%')
                        ->orWhereHas('zone', function($q) use ($search) {
                            $q->where('name', 'like', '%'.$search.'%');
                        })
                        ->orWhereHas('floor', function($q) use ($search) {
                            $q->where('name', 'like', '%'.$search.'%');
                        })
                        ->orWhereHas('brand', function($q) use ($search) {
                            $q->where('name', 'like', '%'.$search.'%');
                        })
                        ->orWhereHas('hydrantType', function($q) use ($search) {
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
        $hydrantTypes = HydrantType::all();
        $extinguisherConditions = ExtinguisherCondition::all();
        $lastHydrant = Hydrant::orderBy('created_at', 'desc')->first();
        return view('admin.hydrant.create', compact('zones', 'buildings', 'floors', 'brands', 'hydrantTypes', 'extinguisherConditions', 'lastHydrant'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHydrantRequest $request)
    {
        // dd($value);
        try {
            $user = Auth::user();

            $validated = $request->validated();

            $validated['user_id'] = $user->id;
            $validated['updated_by'] = $user->id;

            Hydrant::create($validated);

            Swal::success([
                'title' => 'Data berhasil disimpan',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('hydrant.index');
        } catch (\Exception $e) {
            Log::error('Data hydrant gagal disimpan : ' . $e->getMessage());

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
    public function show(Hydrant $hydrant)
    {
        $zones = Zone::all();
        $buildings = Building::all();
        $floors = Floor::all();
        $brands = Brand::all();
        $hydrantTypes = HydrantType::all();
        $extinguisherConditions = ExtinguisherCondition::all();
        return view('admin.hydrant.show', compact('hydrant', 'zones', 'buildings', 'floors', 'brands', 'hydrantTypes', 'extinguisherConditions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Hydrant $hydrant)
    {
        $zones = Zone::all();
        $buildings = Building::all();
        $floors = Floor::all();
        $brands = Brand::all();
        $hydrantTypes = HydrantType::all();
        $extinguisherConditions = ExtinguisherCondition::all();
        return view('admin.hydrant.edit', compact('hydrant', 'zones', 'buildings', 'floors', 'brands', 'hydrantTypes', 'extinguisherConditions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHydrantRequest $request, Hydrant $hydrant)
    {
        // dd($hydrant);
        try {
            $user = Auth::user();

            $validated = $request->validated();

            $validated['updated_by'] = $user->id;

            $hydrant->update($validated);

            Swal::success([
                'title' => 'Data berhasil diperbarui',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('hydrant.index');
        } catch (\Exception $e) {
            Log::error('Data Hydrant gagal diperbarui : ' . $e->getMessage());

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
    public function destroy(Hydrant $hydrant)
    {
        try {
            $hydrant->delete();

            Swal::success([
                'title' => 'Data berhasil dihapus',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('hydrant.index');
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
    public function downloadQrCodeSvg(Hydrant $hydrant)
    {
        if (!$hydrant->qr_code) {
            return redirect()->back()->with('error', 'QR Code tidak tersedia');
        }

        $qrCode = QrCode::size(400)
            ->color(0, 0, 0)
            ->backgroundColor(255, 255, 255)
            ->generate($hydrant->getQrCodeContent());
        
        $filename = 'qrcode-hydrant-' . $hydrant->number_hydrant . '.svg';
        
        return response($qrCode)
            ->header('Content-type', 'image/svg+xml')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
