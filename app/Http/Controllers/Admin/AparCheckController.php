<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apar;
use App\Models\AparCheck;
use App\Models\AparCylinder;
use App\Models\AparHandle;
use App\Models\AparHose;
use App\Models\AparPinSeal;
use App\Models\AparPressure;
use App\Models\Building;
use App\Models\ExtinguisherCondition;
use App\Models\Group;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Middleware\PermissionMiddleware;
use SweetAlert2\Laravel\Swal;
use Yajra\DataTables\Facades\DataTables;

class AparCheckController extends Controller implements HasMiddleware
{
    
    /**
     * role & permission
     */
    public static function middleware()
    {
        return [
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('apar.check.view'), only:['index', 'getAparChecksData', 'aparToCheck', 'show']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('apar.check.create'), only:['scan','create', 'store', 'validateApar']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('apar.check.update'), only:['update', 'edit']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('apar.check.destroy'), only:['destroy']),
        ];
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->getAparChecksData($request);
        }

        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('superadmin');
        $isAdmin = $user->hasRole('admin');

        // Get filter data
        $apars = Apar::where('is_active', true)->orderBy('number_apar')->get();
        
        // Filter zones, buildings, groups, users berdasarkan role
        if ($isSuperAdmin || $isAdmin) {
            // Admin dan superadmin melihat semua data
            $zones = Zone::orderBy('name')->get();
            $buildings = Building::orderBy('name')->get();
            $groups = Group::orderBy('name')->get();
            $users = User::orderBy('name')->get();
        } else {
            // User biasa hanya melihat data sesuai regu (group) mereka
            $assignedZoneIds = $user->group ? $user->group->getAssignedZoneIds() : [];
            
            $zones = Zone::whereIn('id', $assignedZoneIds)->orderBy('name')->get();
            // Ambil buildings dari AparCheck yang ada di zona yang ditugaskan
            $buildingIds = AparCheck::whereIn('zone_id', $assignedZoneIds)
                ->where('group_id', $user->group_id)
                ->distinct()
                ->pluck('building_id')
                ->filter()
                ->toArray();
            $buildings = Building::whereIn('id', $buildingIds)->orderBy('name')->get();
            $groups = Group::where('id', $user->group_id)->orderBy('name')->get();
            $users = User::where('group_id', $user->group_id)->orderBy('name')->get();
        }
        
        $conditions = ExtinguisherCondition::orderBy('name')->get();

        return view('admin.apar-check.index', compact(
            'apars', 'zones', 'buildings', 'groups', 'users', 'conditions'
        ));
    }

     /**
     * Datatable server-side processing
     */
    public function getAparChecksData(Request $request)
    {
        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('superadmin');
        $isAdmin = $user->hasRole('admin');

        $query = AparCheck::with([
            'user',
            'apar',
            'zone', 
            'building',
            'group',
            'condition',
            'pressure',
            'cylinder',
            'pinSeal', 
            'hose',
            'handle'
        ])->latest();

        // Filter berdasarkan role: user biasa hanya melihat data sesuai regu (group) mereka
        if (!$isSuperAdmin && !$isAdmin) {
            if ($user->group_id) {
                $query->where('group_id', $user->group_id);
            } else {
                // Jika user tidak punya group, tidak tampilkan data apapun
                $query->whereRaw('1 = 0');
            }
        }

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->addColumn('apar_number', function($check) {
                return $check->apar ? $check->apar->number_apar : '-';
            })
            ->addColumn('apar_location', function($check) {
                return $check->apar ? $check->apar->location : $check->location;
            })
            ->addColumn('user_name', function($check) {
                return $check->user ? $check->user->name : '-';
            })
            ->addColumn('zone_name', function($check) {
                return $check->zone ? $check->zone->name : '-';
            })
            ->addColumn('building_name', function($check) {
                return $check->building ? $check->building->name : '-';
            })
            ->addColumn('group_name', function($check) {
                return $check->group ? $check->group->name : '-';
            })
            ->addColumn('pressure_name', function($check) {
                return $check->pressure ? $check->pressure->name : '-';
            })
            ->addColumn('cylinder_name', function($check) {
                return $check->cylinder ? $check->cylinder->name : '-';
            })
            ->addColumn('pin_seal_name', function($check) {
                return $check->pinSeal ? $check->pinSeal->name : '-';
            })
            ->addColumn('hose_name', function($check) {
                return $check->hose ? $check->hose->name : '-';
            })
            ->addColumn('handle_name', function($check) {
                return $check->handle ? $check->handle->name : '-';
            })
            ->addColumn('condition_badge', function($check) {
                if (!$check->condition) return '-';
                
                $badgeClass = 'bg-secondary';
                if ($check->condition->name == 'Baik') $badgeClass = 'bg-success';
                if ($check->condition->name == 'Rusak') $badgeClass = 'bg-danger';
                if ($check->condition->name == 'Perbaikan') $badgeClass = 'bg-warning';
                
                return '<span class="badge ' . $badgeClass . '">' . $check->condition->name . '</span>';
            })
            ->addColumn('formatted_date', function($check) {
                return $check->date_check->format('d/m/Y');
            })
            // ->addColumn('check_details', function($check) {
            //     $details = [];
            //     if ($check->pressure) $details[] = $check->pressure->name;
            //     if ($check->cylinder) $details[] = $check->cylinder->name;
            //     if ($check->pinSeal) $details[] = $check->pinSeal->name;
            //     if ($check->hose) $details[] = $check->hose->name;
            //     if ($check->handle) $details[] = $check->handle->name;
                
            //     return implode(', ', $details);
            // })
            ->addColumn('action', function($check) {
                $loggedInUserId = Auth::id(); 
            
                $actionButtons = '';
                $deleteButton = '';
                $editButton = '';
            
                if ($loggedInUserId == $check->user_id) {
                    $editButton = '
                        <a href="' . route('apar-check.edit', $check->id) . '" class="btn btn-sm btn-light" style="border: 1px solid rgba(0,0,0,0.15)" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>';
                        
                    $deleteButton = '
                        <button type="button" class="btn btn-sm btn-light" style="border: 1px solid rgba(0,0,0,0.15)" onclick="confirmDelete(' . $check->id . ')" title="Delete">
                            <i class="fas fa-trash text-danger"></i>
                        </button>
                        <form action="' . route('apar-check.destroy', $check->id) . '" id="delete-form-' . $check->id . '" method="post" class="d-none">
                            ' . csrf_field() . method_field('DELETE') . '
                        </form>';
                }
            
                // Gabungkan semua tombol, tombol View selalu ditampilkan
                $actionButtons = '
                    <div class="d-inline-flex align-items-center gap-1">
                        <a href="' . route('apar-check.show', $check->id) . '" class="btn btn-sm btn-light" style="border: 1px solid rgba(0,0,0,0.15)" title="View Detail">
                            <i class="fas fa-eye text-primary"></i>
                        </a>
                        
                        ' . $editButton . ' 
            
                        ' . $deleteButton . '
                    </div>
                ';
            
                return $actionButtons;
            })
            ->filter(function($query) use ($request) {
                // Global search - check both custom search and DataTables default search
                $searchValue = null;
                if ($request->filled('search')) {
                    $searchValue = $request->search;
                } elseif ($request->has('search.value') && !empty($request->search['value'])) {
                    $searchValue = $request->search['value'];
                }
                
                if ($searchValue) {
                    $query->where(function($q) use ($searchValue) {
                        $q->where('location', 'like', "%$searchValue%")
                          ->orWhereHas('apar', function($q) use ($searchValue) {
                              $q->where('number_apar', 'like', "%$searchValue%");
                          })
                          ->orWhereHas('user', function($q) use ($searchValue) {
                              $q->where('name', 'like', "%$searchValue%");
                          })
                          ->orWhereHas('zone', function($q) use ($searchValue) {
                              $q->where('name', 'like', "%$searchValue%");
                          })
                          ->orWhereHas('building', function($q) use ($searchValue) {
                              $q->where('name', 'like', "%$searchValue%");
                          })
                          ->orWhereHas('group', function($q) use ($searchValue) {
                              $q->where('name', 'like', "%$searchValue%");
                          })
                          ->orWhereHas('condition', function($q) use ($searchValue) {
                              $q->where('name', 'like', "%$searchValue%");
                          });
                    });
                }

                // Individual filters
                if ($request->filled('apar_id')) {
                    $query->where('apar_id', $request->apar_id);
                }

                if ($request->filled('date_check')) {
                    $query->whereDate('date_check', $request->date_check);
                }

                if ($request->filled('date_range_start') && $request->filled('date_range_end')) {
                    $query->whereBetween('date_check', [
                        $request->date_range_start,
                        $request->date_range_end
                    ]);
                }

                if ($request->filled('zone_id')) {
                    $query->where('zone_id', $request->zone_id);
                }

                if ($request->filled('building_id')) {
                    $query->where('building_id', $request->building_id);
                }

                if ($request->filled('group_id')) {
                    $query->where('group_id', $request->group_id);
                }

                if ($request->filled('user_id')) {
                    $query->where('user_id', $request->user_id);
                }

                if ($request->filled('condition_id')) {
                    $query->where('extinguisher_condition_id', $request->condition_id);
                }
            })
            ->rawColumns(['condition_badge', 'action'])
            ->make(true);
    }

     /**
     * Show QR code scan form
     */
    public function scan()
    {
        return view('admin.apar-check.scan');
    }

    /** Validasi APAR dari QR atau input manual */
public function validateApar(Request $request)
{
    $request->validate([
        'qr_code' => 'required|string|max:255',
    ]);

    $barcode = $request->qr_code;

    $apar = Apar::with(['zone', 'building', 'user', 'floor', 'brand', 'aparType', 'extinguisherCondition'])
            ->where('is_active', true) // Perbaikan: gunakan true, bukan $true
            ->where(function($query) use ($barcode) {
                $query->where('qr_code', $barcode)
                      ->orWhere('number_apar', $barcode);
            })
            ->first();

    if (!$apar) {
        $message = 'APAR dengan kode tersebut tidak ditemukan.';
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $message,
            ], 404);
        }

        return redirect()->back()
                ->with('error', $message)
                ->withInput();
    }

    // Validasi akses user ke APAR ini
    if (!$this->userCanCheckApar($apar)) {
        $message = 'Anda tidak memiliki akses untuk mengecek APAR ini.';
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $message,
            ], 403);
        }

        return redirect()->route('apar-check.scan')
            ->with('error', $message);
    }

    // Set session untuk validasi di form create
    session(['scanned_apar_id' => $apar->id]);

    $redirectUrl = route('apar-check.create', $apar->id);

    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'APAR ditemukan: ' . $apar->number_apar,
            'data' => [
                'redirect_url' => $redirectUrl,
            ],
        ]);
    }
    
    // Redirect langsung ke form create
    return redirect()->route('apar-check.create', $apar->id);
}

    /** Helper JSON error response */
private function jsonError($message, $status = 400)
{
    return response()->json([
        'success' => false,
        'message' => $message,
    ], $status);
}

    /**
     * Show create form for APAR check
     */
    public function create(Apar $apar)
    {

        $user = Auth::user();
    
        // Validasi: user harus melalui scan terlebih dahulu
        if (!session()->has('scanned_apar_id') || session('scanned_apar_id') != $apar->id) {
            return redirect()->route('apar-check.scan')
                ->with('error', 'Silakan scan barcode APAR terlebih dahulu.');
        }

        // Validasi akses user ke APAR ini
        if (!$this->userCanCheckApar($apar)) {
            return redirect()->route('apar-check.scan')
                ->with('error', 'Anda tidak memiliki akses untuk mengecek APAR ini.');
        }

        $zones = $user->group ? $user->group->zones : collect();
        
        if ($zones->isEmpty()) {
            return redirect()->route('apar-check.scan')
                ->with('error', 'Anda tidak memiliki zona yang ditugaskan. Silakan hubungi administrator.');
        }

        // Get options for dropdowns
        $pressures = AparPressure::all();
        $cylinders = AparCylinder::all();
        $pinSeals = AparPinSeal::all();
        $hoses = AparHose::all();
        $handles = AparHandle::all();
        $conditions = ExtinguisherCondition::all();

        return view('admin.apar-check.create', compact('apar', 'zones', 'pressures', 'cylinders', 'pinSeals', 'hoses', 'handles', 'conditions'));
    }

    /**
     * Store APAR check
     */
    public function store(Request $request, Apar $apar)
    {
        $user = Auth::user();

        if (!session()->has('scanned_apar_id') || session('scanned_apar_id') != $apar->id) {
            return redirect()->route('apar-check.scan')
                ->with('error', 'Sesi scan tidak valid. Silakan scan ulang APAR.');
        }

        if (!$user->group) {
            return redirect()->back()
                ->with('error', 'Anda tidak memiliki group assignment.')
                ->withInput();
        }

        if (!$user->group->zones->contains('id', $apar->zone_id)) {
            return redirect()->back()
                ->with('error', 'Anda tidak memiliki akses untuk mengecek APAR di zona ini.')
                ->withInput();
        }

        if (!$this->userCanCheckApar($apar)) {
            return redirect()->route('apar-check.scan')
                ->with('error', 'Anda tidak memiliki akses untuk mengecek APAR ini.');
        }

        $validated = $request->validate([
            'apar_pressure_id' => 'required|exists:apar_pressures,id',
            'apar_cylinder_id' => 'required|exists:apar_cylinders,id',
            'apar_pin_seal_id' => 'required|exists:apar_pin_seals,id',
            'apar_hose_id' => 'required|exists:apar_hoses,id',
            'apar_handle_id' => 'required|exists:apar_handles,id',
            'extinguisher_condition_id' => 'required|exists:extinguisher_conditions,id',
            'notes' => 'nullable|string|max:1000'
        ]);

        try {
            $aparCheck = AparCheck::create([
                'user_id' => Auth::id(),
                'apar_id' => $apar->id,
                'group_id' => $user->group_id,
                'date_check' => now()->format('Y-m-d'),
                'zone_id' => $apar->zone_id,
                'building_id' => $apar->building_id,
                'location' => $apar->location,
                'apar_pressure_id' => $validated['apar_pressure_id'],
                'apar_cylinder_id' => $validated['apar_cylinder_id'],
                'apar_pin_seal_id' => $validated['apar_pin_seal_id'],
                'apar_hose_id' => $validated['apar_hose_id'],
                'apar_handle_id' => $validated['apar_handle_id'],
                'extinguisher_condition_id' => $validated['extinguisher_condition_id'],
                'notes' => $validated['notes']
            ]);

            Swal::success([
                'title' => 'Data berhasil disimpan',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            // return redirect()->route('apar-check.show', $aparCheck->id)
            return redirect()->route('apar-check.index')
                ->with('success', 'Pengecekan APAR berhasil disimpan!');

        } catch (\Exception $e) {

            Log::error('Data gagal disimpan :' . $e->getMessage());


            Swal::error([
                'title' => 'Data gagal disimpan',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan pengecekan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resourece
     */
    public function show(AparCheck $aparCheck)
    {
        $pressures = AparPressure::all();
        $cylinders = AparCylinder::all();
        $pinSeals = AparPinSeal::all();
        $hoses = AparHose::all();
        $handles = AparHandle::all();
        $conditions = ExtinguisherCondition::all();

        return view('admin.apar-check.show', compact('aparCheck', 'pressures', 'cylinders', 'pinSeals', 'hoses', 'handles', 'conditions'));
    }

    public function edit(AparCheck $aparCheck)
    {
        $user = Auth::user();
        
        // Authorization check - superadmin, admin, atau pemilik checklist bisa edit
        $isSuperAdmin = $user->hasRole('superadmin');
        $isAdmin = $user->hasRole('admin');
        $isOwner = $aparCheck->user_id != null && (int)$aparCheck->user_id === (int)$user->id;
        
        if (!$isSuperAdmin && !$isAdmin && !$isOwner) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit pengecekan ini.');
        }

        $apar = $aparCheck->apar;
        $zones = $user->group ? $user->group->zones : collect();

        // Get options for dropdowns
        $pressures = AparPressure::all();
        $cylinders = AparCylinder::all();
        $pinSeals = AparPinSeal::all();
        $hoses = AparHose::all();
        $handles = AparHandle::all();
        $conditions = ExtinguisherCondition::all();

        return view('admin.apar-check.edit', compact(
            'aparCheck', 'apar', 'zones', 'pressures', 'cylinders', 'pinSeals', 'hoses', 'handles', 'conditions'
        ));
    }

    /**
     * Update APAR check
     */
    public function update(Request $request, AparCheck $aparCheck)
    {
        $user = Auth::user();
        
        // Authorization check - superadmin, admin, atau pemilik checklist bisa update
        $isSuperAdmin = $user->hasRole('superadmin');
        $isAdmin = $user->hasRole('admin');
        $isOwner = $aparCheck->user_id != null && (int)$aparCheck->user_id === (int)$user->id;
        
        if (!$isSuperAdmin && !$isAdmin && !$isOwner) {
            abort(403, 'Anda tidak memiliki akses untuk mengupdate pengecekan ini.');
        }

        $validated = $request->validate([
            'apar_pressure_id' => 'required|exists:apar_pressures,id',
            'apar_cylinder_id' => 'required|exists:apar_cylinders,id',
            'apar_pin_seal_id' => 'required|exists:apar_pin_seals,id',
            'apar_hose_id' => 'required|exists:apar_hoses,id',
            'apar_handle_id' => 'required|exists:apar_handles,id',
            'extinguisher_condition_id' => 'required|exists:extinguisher_conditions,id',
            'notes' => 'nullable|string|max:1000'
        ]);

        try {
            $aparCheck->update([
                'apar_pressure_id' => $validated['apar_pressure_id'],
                'apar_cylinder_id' => $validated['apar_cylinder_id'],
                'apar_pin_seal_id' => $validated['apar_pin_seal_id'],
                'apar_hose_id' => $validated['apar_hose_id'],
                'apar_handle_id' => $validated['apar_handle_id'],
                'extinguisher_condition_id' => $validated['extinguisher_condition_id'],
                'notes' => $validated['notes'] ?? null
            ]);

            Swal::success([
                'title' => 'Data berhasil diperbarui',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('apar-check.show', $aparCheck->id)
                ->with('success', 'Pengecekan APAR berhasil diperbarui!');

        } catch (\Exception $e) {
            Log::error('Data gagal diperbarui :' . $e->getMessage());

            Swal::error([
                'title' => 'Data gagal diperbarui',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui pengecekan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AparCheck $aparCheck)
    {
        try {
            $aparCheck->delete();

            Swal::success([
                'title' => 'Data berhasil dihapus',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('apar-check.to-check');

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
     * Check if user can check this APAR (berdasarkan zona/group assignment)
     */
    private function userCanCheckApar(Apar $apar)
    {
        // Logic untuk cek apakah user boleh mengecek APAR ini
        // Contoh: berdasarkan zona yang ditugaskan ke user
        // Anda bisa menyesuaikan dengan business logic Anda
        
        // Contoh sederhana: user bisa mengecek APAR di zona yang sama dengan group user
        // return $user->group_id === $apar->group_id;
        // return true;
        return Auth::user()->group->zones->contains('id', $apar->zone_id);

        
        // Atau lebih kompleks: berdasarkan assignment khusus
        // return $user->zones->contains($apar->zone_id);
    }


    // =========================================== apar to check ===============================================
    /**
     * Display APAR list that need to be checked by current user's group
     * dengan server-side rendering
     */
    // public function aparToCheck(Request $request)
    // {
    //     $user = Auth::user();
        
    //     if (!$user->group) {

    //         Swal::error([
    //             'title' => 'Anda tidak memiliki group',
    //             'showConfirmButton' => false,
    //             'timer' => 2000
    //         ]);

    //         return redirect()->back()
    //             ->with('error', 'Anda tidak memiliki group assignment.');
    //     }

    //     // Dapatkan zona yang diassign ke group user
    //     $assignedZoneIds = $user->group->getAssignedZoneIds();

    //     if (empty($assignedZoneIds)) {
    //         return view('admin.apar-check.to-check', [
    //             'apars' => collect(),
    //             'assignedZones' => collect(),
    //             'filter' => $request->filter,
    //             'totalApars' => 0,
    //             'checkedCount' => 0,
    //             'uncheckedCount' => 0,
    //             'progress' => 0
    //         ])->with('info', 'Tidak ada zona yang ditugaskan ke group Anda.');
    //     }

    //     $currentMonth = now()->month;
    //     $currentYear = now()->year;
        
    //     // Dapatkan APAR yang sudah di-checklist bulan ini oleh SEMUA USER dalam GROUP YANG SAMA
    //     $checkedAparIds = AparCheck::whereHas('user', function ($query) use ($user) {
    //             $query->where('group_id', $user->group_id);
    //         })
    //         ->whereMonth('date_check', $currentMonth)
    //         ->whereYear('date_check', $currentYear)
    //         ->pluck('apar_id')
    //         ->toArray();

    //     // Dapatkan ID AparCheck terbaru untuk setiap APAR yang sudah dicek
    //     $latestAparCheckIds = [];
    //     if (!empty($checkedAparIds)) {
    //         $latestChecks = AparCheck::whereHas('user', function ($query) use ($user) {
    //                 $query->where('group_id', $user->group_id);
    //             })
    //             ->whereMonth('date_check', $currentMonth)
    //             ->whereYear('date_check', $currentYear)
    //             ->whereIn('apar_id', $checkedAparIds)
    //             ->orderBy('apar_id')
    //             ->orderByDesc('id')
    //             ->get()
    //             ->groupBy('apar_id')
    //             ->map(function ($checks) {
    //                 return $checks->first()->id;
    //             });
            
    //         $latestAparCheckIds = $latestChecks->toArray();
    //     }

    //     // QUERY UNTUK SUMMARY (TERPISAH DARI PAGINATION)
    //     $summaryQuery = Apar::whereIn('zone_id', $assignedZoneIds)
    //         ->where('is_active', '1');
        
    //     $totalApars = $summaryQuery->count();
    //     $checkedCount = $summaryQuery->whereIn('id', $checkedAparIds)->count();
    //     $uncheckedCount = $totalApars - $checkedCount;
    //     $progress = $totalApars > 0 ? round(($checkedCount / $totalApars) * 100, 1) : 0;

    //     // QUERY UNTUK PAGINATION (FILTER BERDASARKAN REQUEST)
    //     $query = Apar::with(['zone', 'building', 'floor', 'brand', 'aparType'])
    //         ->whereIn('zone_id', $assignedZoneIds)
    //         ->where('is_active', '1');

    //     // Filter berdasarkan status
    //     $filter = $request->filter;
    //     if ($filter === 'checked') {
    //         $query->whereIn('id', $checkedAparIds);
    //     } elseif ($filter === 'unchecked') {
    //         $query->whereNotIn('id', $checkedAparIds);
    //     }

    //     // Search
    //     if ($request->has('search') && !empty($request->search)) {
    //         $search = $request->search;
    //         $query->where(function($q) use ($search) {
    //             $q->where('number_apar', 'like', "%{$search}%")
    //             ->orWhere('location', 'like', "%{$search}%")
    //             ->orWhereHas('zone', function($q) use ($search) {
    //                 $q->where('name', 'like', "%{$search}%");
    //             })
    //             ->orWhereHas('building', function($q) use ($search) {
    //                 $q->where('name', 'like', "%{$search}%");
    //             });
    //         });
    //     }

    //     // Sorting
    //     $sort = $request->sort ?? 'zone_id';
    //     $direction = $request->direction ?? 'asc';
        
    //     $allowedSorts = ['number_apar', 'zone_id', 'location', 'building_id'];
    //     if (in_array($sort, $allowedSorts)) {
    //         $query->orderBy($sort, $direction);
    //     } else {
    //         $query->orderBy('zone_id')->orderBy('number_apar');
    //     }

    //     $apars = $query->paginate(3)->withQueryString();

    //     // Tambahkan status checked bulan ini dan ID AparCheck terbaru ke setiap APAR
    //     $apars->each(function ($apar) use ($checkedAparIds, $latestAparCheckIds) {
    //         $apar->is_checked_this_month = in_array($apar->id, $checkedAparIds);
    //         $apar->latest_check_id = $latestAparCheckIds[$apar->id] ?? null;
    //     });

    //     $assignedZones = Zone::whereIn('id', $assignedZoneIds)->get();

    //     // Untuk download Excel
    //     if ($request->has('download')) {
    //         return $this->downloadExcel($apars->get(), $filter);
    //     }

    //     return view('admin.apar-check.to-check', compact(
    //         'apars', 
    //         'assignedZones', 
    //         'filter', 
    //         'sort', 
    //         'direction',
    //         'totalApars',
    //         'checkedCount',
    //         'uncheckedCount',
    //         'progress'
    //     ));
    // }

    public function aparToCheck(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->group) {
            Swal::error([
                'title' => 'Anda tidak memiliki group',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);
            return redirect()->back()->with('error', 'Anda tidak memiliki group assignment.');
        }

        // Dapatkan zona yang diassign ke group user
        $assignedZoneIds = $user->group->getAssignedZoneIds();

        if (empty($assignedZoneIds)) {
            return view('admin.apar-check.to-check', [
                'assignedZones' => collect(),
                'filter' => $request->filter,
                'totalApars' => 0,
                'checkedCount' => 0,
                'uncheckedCount' => 0,
                'progress' => 0
            ])->with('info', 'Tidak ada zona yang ditugaskan ke group Anda.');
        }

        // Jika request AJAX untuk Datatables
        if ($request->ajax()) {
            return $this->getAparData($request, $user, $assignedZoneIds);
        }

        // Untuk initial page load - hitung summary saja
        $summary = $this->getSummaryData($user, $assignedZoneIds, $request->filter);

        $assignedZones = Zone::whereIn('id', $assignedZoneIds)->get();

        return view('admin.apar-check.to-check', array_merge($summary, [
            'assignedZones' => $assignedZones,
            'filter' => $request->filter
        ]));
    }

    private function getAparData(Request $request, $user, $assignedZoneIds)
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        // Dapatkan APAR yang sudah di-checklist bulan ini
        $checkedAparIds = AparCheck::whereHas('user', function ($query) use ($user) {
                $query->where('group_id', $user->group_id);
            })
            ->whereMonth('date_check', $currentMonth)
            ->whereYear('date_check', $currentYear)
            ->pluck('apar_id')
            ->toArray();

        // Dapatkan ID AparCheck terbaru
        $latestAparCheckIds = [];
        if (!empty($checkedAparIds)) {
            $latestChecks = AparCheck::whereHas('user', function ($query) use ($user) {
                    $query->where('group_id', $user->group_id);
                })
                ->whereMonth('date_check', $currentMonth)
                ->whereYear('date_check', $currentYear)
                ->whereIn('apar_id', $checkedAparIds)
                ->orderBy('apar_id')
                ->orderByDesc('id')
                ->get()
                ->groupBy('apar_id')
                ->map(function ($checks) {
                    return $checks->first()->id;
                });
            
            $latestAparCheckIds = $latestChecks->toArray();
        }

        $query = Apar::with(['zone', 'building', 'floor', 'brand', 'aparType'])
            ->whereIn('zone_id', $assignedZoneIds)
            ->where('is_active', '1');

        // Filter berdasarkan status
        $filter = $request->filter;
        if ($filter === 'checked') {
            $query->whereIn('id', $checkedAparIds);
        } elseif ($filter === 'unchecked') {
            $query->whereNotIn('id', $checkedAparIds);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('no', function($apar) {
                return '';
            })
            ->addColumn('status_badge', function($apar) use ($checkedAparIds) {
                $isChecked = in_array($apar->id, $checkedAparIds);
                if ($isChecked) {
                    return '<span class="badge bg-success"><i class="fas fa-check"></i> Sudah</span>';
                } else {
                    return '<span class="badge bg-warning"><i class="fas fa-times"></i> Belum</span>';
                }
            })
            ->addColumn('action', function($apar) use ($checkedAparIds, $latestAparCheckIds) {
                $isChecked = in_array($apar->id, $checkedAparIds);
                $latestCheckId = $latestAparCheckIds[$apar->id] ?? null;

                if ($isChecked && $latestCheckId) {
                    return '<a href="'.route('apar-check.edit', $latestCheckId).'" class="btn btn-info btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>';
                } else {
                    return '<a href="'.route('apar-check.create', $apar->id).'" class="btn btn-primary btn-sm">
                                <i class="fas fa-clipboard-check"></i> Checklist
                            </a>';
                }
            })
            ->addColumn('zone_name', function($apar) {
                return '<span class="badge bg-info">'.$apar->zone->name.'</span>';
            })
            ->addColumn('building_name', function($apar) {
                return $apar->building->name ?? '-';
            })
            ->addColumn('floor_name', function($apar) {
                return $apar->floor->name ?? '-';
            })
            ->addColumn('brand_name', function($apar) {
                return $apar->brand->name ?? '-';
            })
            ->addColumn('apar_type_name', function($apar) {
                return $apar->aparType->name ?? '-';
            })
            ->addColumn('status_info', function($apar) use ($checkedAparIds) {
                $isChecked = in_array($apar->id, $checkedAparIds);
                if ($isChecked) {
                    return '<small class="text-success"><i class="fas fa-check-circle"></i> Sudah diperiksa bulan ini</small>';
                } else {
                    return '<small class="text-warning"><i class="fas fa-clock"></i> Belum diperiksa bulan ini</small>';
                }
            })
            ->filter(function ($query) use ($request) {
                if (!empty($request->search['value'])) {
                    $search = $request->search['value'];
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
            })
            ->rawColumns(['status_badge', 'action', 'zone_name', 'status_info'])
            ->make(true);
    }

    private function getSummaryData($user, $assignedZoneIds, $filter = null)
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        // Dapatkan APAR yang sudah di-checklist bulan ini
        $checkedAparIds = AparCheck::whereHas('user', function ($query) use ($user) {
                $query->where('group_id', $user->group_id);
            })
            ->whereMonth('date_check', $currentMonth)
            ->whereYear('date_check', $currentYear)
            ->pluck('apar_id')
            ->toArray();

        // Query untuk summary
        $summaryQuery = Apar::whereIn('zone_id', $assignedZoneIds)
            ->where('is_active', '1');

        $totalApars = $summaryQuery->count();
        $checkedCount = $summaryQuery->whereIn('id', $checkedAparIds)->count();
        $uncheckedCount = $totalApars - $checkedCount;
        $progress = $totalApars > 0 ? round(($checkedCount / $totalApars) * 100, 1) : 0;

        return [
            'totalApars' => $totalApars,
            'checkedCount' => $checkedCount,
            'uncheckedCount' => $uncheckedCount,
            'progress' => $progress
        ];
    }

    // =========================================== end apar to check ===============================================


}
