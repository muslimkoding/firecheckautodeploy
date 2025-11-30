<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Zone;
use App\Models\Group;
use App\Models\Hydrant;
use App\Models\Building;
use App\Models\HydrantDoor;
use App\Models\HydrantHose;
use App\Models\HydrantCheck;
use App\Models\HydrantGuide;
use Illuminate\Http\Request;
use App\Models\HydrantNozzle;
use SweetAlert2\Laravel\Swal;
use App\Models\HydrantCoupling;
use App\Models\HydrantMainValve;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\HydrantSafetyMarking;
use Illuminate\Support\Facades\Auth;
use App\Models\ExtinguisherCondition;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class HydrantCheckController extends Controller implements HasMiddleware
{
    /**
     * role & permission
     */
    public static function middleware()
    {
        return [
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('hydrant.check.view'), only:['index', 'getHydrantChecksData', 'hydrantToCheck', 'show']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('hydrant.check.create'), only:['scan','create', 'store', 'validateHydrant']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('hydrant.check.update'), only:['update', 'edit']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('hydrant.check.destroy'), only:['destroy']),
        ];
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->getHydrantChecksData($request);
        }

        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('superadmin');
        $isAdmin = $user->hasRole('admin');

        // Get filter data
        $hydrants = Hydrant::where('is_active', true)->orderBy('number_hydrant')->get();
        
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
            // Ambil buildings dari HydrantCheck yang ada di zona yang ditugaskan
            $buildingIds = HydrantCheck::whereIn('zone_id', $assignedZoneIds)
                ->where('group_id', $user->group_id)
                ->distinct()
                ->pluck('building_id')
                ->filter()
                ->toArray();
            $buildings = Building::whereIn('id', $buildingIds)->orderBy('name')->get();
            $groups = Group::where('id', $user->group_id)->orderBy('name')->get();
            $users = User::where('group_id', $user->group_id)->orderBy('name')->get();
        }

        // Get filter data
        // $hydrants = Hydrant::where('is_active', true)->orderBy('number_hydrant')->get();
        // $zones = Zone::orderBy('name')->get();
        // $buildings = Building::orderBy('name')->get();
        // $groups = Group::orderBy('name')->get();
        // $users = User::orderBy('name')->get();
        $conditions = ExtinguisherCondition::orderBy('name')->get();

        return view('admin.hydrant-check.index', compact(
            'hydrants',
            'zones',
            'buildings',
            'groups',
            'users',
            'conditions'
        ));
    }

    /**
     * Datatable server-side processing
     */
    public function getHydrantChecksData(Request $request)
    {
        $user = Auth::user();
        $isSuperAdmin = $user->hasRole('superadmin');
        $isAdmin = $user->hasRole('admin');

        $query = HydrantCheck::with([
            'user',
            'hydrant',
            'zone',
            'building',
            'group',
            'condition',
            'hydrantDoor',
            'hydrantCoupling',
            'hydrantMainValve',
            'hydrantHose',
            'hydrantNozzle',
            'hydrantSafetyMarking',
            'hydrantGuide'
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
            ->addColumn('number_hydrant', function ($check) {
                return $check->hydrant ? $check->hydrant->number_hydrant : '-';
            })
            ->addColumn('hydrant_location', function ($check) {
                return $check->hydrant ? $check->hydrant->location : $check->location;
            })
            ->addColumn('user_name', function ($check) {
                return $check->user ? $check->user->name : '-';
            })
            ->addColumn('zone_name', function ($check) {
                return $check->zone ? $check->zone->name : '-';
            })
            ->addColumn('building_name', function ($check) {
                return $check->building ? $check->building->name : '-';
            })
            ->addColumn('group_name', function ($check) {
                return $check->group ? $check->group->name : '-';
            })
            ->addColumn('hydrant_door', function ($check) {
                return $check->hydrantDoor ? $check->hydrantDoor->name : '-';
            })
            ->addColumn('hydrant_coupling', function ($check) {
                return $check->hydrantCoupling ? $check->hydrantCoupling->name : '-';
            })
            ->addColumn('hydrant_main_valve', function ($check) {
                return $check->hydrantMainValve ? $check->hydrantMainValve->name : '-';
            })
            ->addColumn('hydrant_hose', function ($check) {
                return $check->hydrantHose ? $check->hydrantHose->name : '-';
            })
            ->addColumn('hydrant_nozzle', function ($check) {
                return $check->hydrantNozzle ? $check->hydrantNozzle->name : '-';
            })
            ->addColumn('hydrant_safety_marking', function ($check) {
                return $check->hydrantSafetyMarking ? $check->hydrantSafetyMarking->name : '-';
            })
            ->addColumn('hydrant_guide', function ($check) {
                return $check->hydrantGuide ? $check->hydrantGuide->name : '-';
            })
            ->addColumn('condition_badge', function ($check) {
                if (!$check->condition) return '-';

                $badgeClass = 'bg-secondary';
                if ($check->condition->name == 'Normal') $badgeClass = 'bg-success';
                if ($check->condition->name == 'Rusak') $badgeClass = 'bg-danger';
                if ($check->condition->name == 'Perlu Perhatian') $badgeClass = 'bg-warning';

                return '<span class="badge ' . $badgeClass . '">' . $check->condition->name . '</span>';
            })
            ->addColumn('formatted_date', function ($check) {
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
            // ->addColumn('action', function ($check) {
            //     return '
            //         <div class="d-inline-flex align-items-center gap-1">
            //             <a href="' . route('hydrant-check.show', $check->id) . '" class="btn btn-sm btn-light" style="border: 1px solid rgba(0,0,0,0.15)" title="View Detail">
            //                 <i class="fas fa-eye text-primary"></i>
            //             </a>
            //             <a href="' . route('hydrant-check.edit', $check->id) . '" class="btn btn-sm btn-light" style="border: 1px solid rgba(0,0,0,0.15)" title="Edit">
            //                 <i class="fas fa-edit"></i>
            //             </a>
            //             <button type="button" class="btn btn-sm btn-light" style="border: 1px solid rgba(0,0,0,0.15)" onclick="confirmDelete(' . $check->id . ')" title="Delete">
            //                 <i class="fas fa-trash text-danger"></i>
            //             </button>
            //             <form action="' . route('hydrant-check.destroy', $check->id) . '" id="delete-form-' . $check->id . '" method="post" class="d-none">
            //                 ' . csrf_field() . method_field('DELETE') . '
            //             </form>
            //         </div>
            //     ';
            // })
            ->addColumn('action', function($check) {
                $loggedInUserId = Auth::id(); 
            
                $actionButtons = '';
                $deleteButton = '';
                $editButton = '';
            
                if ($loggedInUserId == $check->user_id) {
                    $editButton = '
                        <a href="' . route('hydrant-check.edit', $check->id) . '" class="btn btn-sm btn-light" style="border: 1px solid rgba(0,0,0,0.15)" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>';
                        
                    $deleteButton = '
                        <button type="button" class="btn btn-sm btn-light" style="border: 1px solid rgba(0,0,0,0.15)" onclick="confirmDelete(' . $check->id . ')" title="Delete">
                            <i class="fas fa-trash text-danger"></i>
                        </button>
                        <form action="' . route('hydrant-check.destroy', $check->id) . '" id="delete-form-' . $check->id . '" method="post" class="d-none">
                            ' . csrf_field() . method_field('DELETE') . '
                        </form>';
                }
            
                // Gabungkan semua tombol, tombol View selalu ditampilkan
                $actionButtons = '
                    <div class="d-inline-flex align-items-center gap-1">
                        <a href="' . route('hydrant-check.show', $check->id) . '" class="btn btn-sm btn-light" style="border: 1px solid rgba(0,0,0,0.15)" title="View Detail">
                            <i class="fas fa-eye text-primary"></i>
                        </a>
                        
                        ' . $editButton . ' 
            
                        ' . $deleteButton . '
                    </div>
                ';
            
                return $actionButtons;
            })
            ->filter(function ($query) use ($request) {
                // Global search - check both custom search and DataTables default search
                $searchValue = null;
                if ($request->filled('search')) {
                    $searchValue = $request->search;
                } elseif ($request->has('search.value') && !empty($request->search['value'])) {
                    $searchValue = $request->search['value'];
                }

                if ($searchValue) {
                    $query->where(function ($q) use ($searchValue) {
                        $q->where('location', 'like', "%$searchValue%")
                            ->orWhereHas('hydrant', function ($q) use ($searchValue) {
                                $q->where('number_hydrant', 'like', "%$searchValue%");
                            })
                            ->orWhereHas('user', function ($q) use ($searchValue) {
                                $q->where('name', 'like', "%$searchValue%");
                            })
                            ->orWhereHas('zone', function ($q) use ($searchValue) {
                                $q->where('name', 'like', "%$searchValue%");
                            })
                            ->orWhereHas('building', function ($q) use ($searchValue) {
                                $q->where('name', 'like', "%$searchValue%");
                            })
                            ->orWhereHas('group', function ($q) use ($searchValue) {
                                $q->where('name', 'like', "%$searchValue%");
                            })
                            ->orWhereHas('condition', function ($q) use ($searchValue) {
                                $q->where('name', 'like', "%$searchValue%");
                            });
                    });
                }

                // Individual filters
                if ($request->filled('hydrant_id')) {
                    $query->where('hydrant_id', $request->hydrant_id);
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
     * Show the form for creating a new resource.
     */
    public function create(Hydrant $hydrant)
    {
        // Authorization check
        // if (!$this->userCanCheckHydrant(Auth::user(), $hydrant)) {
        //     abort(403, 'Anda tidak memiliki akses untuk mengecek Hydrant ini');
        // }

        $user = Auth::user();

        // Validasi: user harus melalui scan terlebih dahulu
        if (!session()->has('scanned_hydrant_id') || session('scanned_hydrant_id') != $hydrant->id) {
            return redirect()->route('hydrant-check.scan')
                ->with('error', 'Silakan scan barcode Hydrant terlebih dahulu.');
        }

        // Validasi akses user ke Hydrant ini
        if (!$this->userCanCheckHydrant($hydrant)) {
            return redirect()->route('hydrant-check.scan')
                ->with('error', 'Anda tidak memiliki akses untuk mengecek Hydrant ini.');
        }

        $zones = $user->group ? $user->group->zones : collect();

        if ($zones->isEmpty()) {
            return redirect()->route('hydrant-check.scan')
                ->with('error', 'Anda tidak memiliki zona yang ditugaskan. Silakan hubungi administrator.');
        }

        // Get options for dropdowns
        $hydrantDoors = HydrantDoor::all();
        $hydrantCouplings = HydrantCoupling::all();
        $hydrantMainValves = HydrantMainValve::all();
        $hydrantHoses = HydrantHose::all();
        $hydrantNozzles = HydrantNozzle::all();
        $hydrantSafetyMarkings = HydrantSafetyMarking::all();
        $hydrantGuides = HydrantGuide::all();
        $conditions = ExtinguisherCondition::all();

        return view('admin.hydrant-check.create', compact(
            'hydrant',
            'hydrantDoors',
            'hydrantCouplings',
            'hydrantMainValves',
            'hydrantHoses',
            'hydrantNozzles',
            'hydrantSafetyMarkings',
            'hydrantGuides',
            'conditions'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Hydrant $hydrant)
    {
        // dd($hydrant);
        $user = Auth::user();

        if (!session()->has('scanned_hydrant_id') || session('scanned_hydrant_id') != $hydrant->id) {
            return redirect()->route('hydrant-check.scan')
                ->with('error', 'Sesi scan tidak valid. Silakan scan ulang Hydrant.');
        }

        if (!$user->group) {
            return redirect()->back()
                ->with('error', 'Anda tidak memiliki group assignment.')
                ->withInput();
        }

        if (!$user->group->zones->contains('id', $hydrant->zone_id)) {
            return redirect()->back()
                ->with('error', 'Anda tidak memiliki akses untuk mengecek Hydrant di zona ini.')
                ->withInput();
        }

        if (!$this->userCanCheckHydrant($hydrant)) {
            return redirect()->route('hydrant-check.scan')
                ->with('error', 'Anda tidak memiliki akses untuk mengecek Hydrant ini.');
        }

        $validated = $request->validate([
            'hydrant_door_id' => 'required|exists:hydrant_doors,id',
            'hydrant_coupling_id' => 'required|exists:hydrant_couplings,id',
            'hydrant_main_valve_id' => 'required|exists:hydrant_main_valves,id',
            'hydrant_hose_id' => 'required|exists:hydrant_hoses,id',
            'hydrant_nozzle_id' => 'required|exists:hydrant_nozzles,id',
            'hydrant_safety_marking_id' => 'required|exists:hydrant_safety_markings,id',
            'hydrant_guide_id' => 'required|exists:hydrant_guides,id',
            // 'hydrant_type_id' => 'required|exists:hydrant_types,id',
            'extinguisher_condition_id' => 'required|exists:extinguisher_conditions,id',
            'notes' => 'nullable|string|max:1000'
        ]);

        try {
            $hydrantCheck = HydrantCheck::create([
                'user_id' => Auth::id(),
                'hydrant_id' => $hydrant->id,
                'hydrant_type_id' => $hydrant->hydrant_type_id,
                'group_id' => $user->group_id,
                'date_check' => now()->format('Y-m-d'),
                'zone_id' => $hydrant->zone_id,
                'building_id' => $hydrant->building_id,
                'location' => $hydrant->location,
                'hydrant_door_id' => $validated['hydrant_door_id'],
                'hydrant_coupling_id' => $validated['hydrant_coupling_id'],
                'hydrant_main_valve_id' => $validated['hydrant_main_valve_id'],
                'hydrant_hose_id' => $validated['hydrant_hose_id'],
                'hydrant_nozzle_id' => $validated['hydrant_nozzle_id'],
                'hydrant_safety_marking_id' => $validated['hydrant_safety_marking_id'],
                'hydrant_guide_id' => $validated['hydrant_guide_id'],
                // 'hydrant_type_id' => $validated['hydrant_type_id'],
                'extinguisher_condition_id' => $validated['extinguisher_condition_id'],
                'notes' => $validated['notes']
            ]);

            Swal::success([
                'title' => 'Data berhasil disimpan',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            // return redirect()->route('hydrant-check.show', $hydrantCheck->id)
            return redirect()->route('hydrant-check.index')
                ->with('success', 'Pengecekan Hydrant berhasil disimpan!');

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
     * Display the specified resource.
     */
    public function show(HydrantCheck $hydrantCheck)
    {
        $hydrantDoors = HydrantDoor::all();
        $hydrantCouplings = HydrantCoupling::all();
        $hydrantMainValves = HydrantMainValve::all();
        $hydrantHoses = HydrantHose::all();
        $hydrantNozzles = HydrantNozzle::all();
        $hydrantSafetyMarkings = HydrantSafetyMarking::all();
        $hydrantGuides = HydrantGuide::all();
        $conditions = ExtinguisherCondition::all();

        return view('admin.hydrant-check.show', compact(
            'hydrantCheck', 'hydrantDoors', 'hydrantCouplings', 'hydrantMainValves', 'hydrantHoses', 'hydrantNozzles', 'conditions', 'hydrantSafetyMarkings', 'hydrantGuides'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HydrantCheck $hydrantCheck)
    {
        $user = Auth::user();
        
        // Authorization check - superadmin, admin, atau pemilik checklist bisa edit
        $isSuperAdmin = $user->hasRole('superadmin');
        $isAdmin = $user->hasRole('admin');
        $isOwner = $hydrantCheck->user_id != null && (int)$hydrantCheck->user_id === (int)$user->id;
        
        if (!$isSuperAdmin && !$isAdmin && !$isOwner) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit pengecekan ini.');
        }

        $hydrant = $hydrantCheck->hydrant;
        $zones = $user->group ? $user->group->zones : collect();

        $hydrantDoors = HydrantDoor::all();
        $hydrantCouplings = HydrantCoupling::all();
        $hydrantMainValves = HydrantMainValve::all();
        $hydrantHoses = HydrantHose::all();
        $hydrantNozzles = HydrantNozzle::all();
        $hydrantSafetyMarkings = HydrantSafetyMarking::all();
        $hydrantGuides = HydrantGuide::all();
        $conditions = ExtinguisherCondition::all();

        return view('admin.hydrant-check.edit', compact(
            'hydrantCheck', 'hydrant', 'zones', 'hydrantDoors', 'hydrantCouplings', 'hydrantMainValves', 'hydrantHoses', 'hydrantNozzles', 'conditions', 'hydrantSafetyMarkings', 'hydrantGuides'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HydrantCheck $hydrantCheck)
    {
        $user = Auth::user();

        // Authorization check - superadmin, admin, atau pemilik checklist bisa menyimpan
        $isSuperAdmin = $user->hasRole('superadmin');
        $isAdmin = $user->hasRole('admin');
        $isOwner = $hydrantCheck->user_id != null && (int)$hydrantCheck->user_id === (int)$user->id;
        
        if (!$isSuperAdmin && !$isAdmin && !$isOwner) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit pengecekan ini.');
        }

        $validated = $request->validate([
            'hydrant_door_id' => 'required|exists:hydrant_doors,id',
            'hydrant_coupling_id' => 'required|exists:hydrant_couplings,id',
            'hydrant_main_valve_id' => 'required|exists:hydrant_main_valves,id',
            'hydrant_hose_id' => 'required|exists:hydrant_hoses,id',
            'hydrant_nozzle_id' => 'required|exists:hydrant_nozzles,id',
            'hydrant_safety_marking_id' => 'required|exists:hydrant_safety_markings,id',
            'hydrant_guide_id' => 'required|exists:hydrant_guides,id',
            'extinguisher_condition_id' => 'required|exists:extinguisher_conditions,id',
            'notes' => 'nullable|string|max:1000'
        ]);

        try {
            $hydrantCheck->update([
                'hydrant_door_id' => $validated['hydrant_door_id'],
                'hydrant_coupling_id' => $validated['hydrant_coupling_id'],
                'hydrant_main_valve_id' => $validated['hydrant_main_valve_id'],
                'hydrant_hose_id' => $validated['hydrant_hose_id'],
                'hydrant_nozzle_id' => $validated['hydrant_nozzle_id'],
                'hydrant_safety_marking_id' => $validated['hydrant_safety_marking_id'],
                'hydrant_guide_id' => $validated['hydrant_guide_id'],
                'extinguisher_condition_id' => $validated['extinguisher_condition_id'],
                'notes' => $validated['notes'] ?? null
            ]);

            Swal::success([
                'title' => 'Data berhasil diperbarui',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            // return redirect()->route('hydrant-check.show', $hydrantCheck->id)
            return redirect()->route('hydrant-check.to-check')
                ->with('success', 'Pengecekan Hydrant berhasil diperbarui!');

        } catch (\Exception $e) {

            Log::error('Data gagal diperbarui :' . $e->getMessage());


            Swal::error([
                'title' => 'Data gagal diperbarui',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menyimpan pengecekan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HydrantCheck $hydrantCheck)
    {
        try {
            $hydrantCheck->delete();

            Swal::success([
                'title' => 'Data berhasil dihapus',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('hydrant-check.to-check');

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

    public function scan()
    {
        return view('admin.hydrant-check.scan');
    }

    /** Validasi Hydrant dari QR atau input manual */
    // public function validateHydrant(Request $request)
    // {
    //     $request->validate([
    //         'qr_code' => 'required|string|max:255',
    //     ]);

    //     $barcode = $request->qr_code;

    //     $hydrant = Hydrant::with(['zone', 'building', 'user', 'floor', 'brand', 'hydrantType', 'extinguisherCondition'])
    //             ->where('is_active', true) // Perbaikan: gunakan true, bukan $true
    //             ->where(function($query) use ($barcode) {
    //                 $query->where('qr_code', $barcode)
    //                     ->orWhere('number_hydrant', $barcode);
    //             })
    //             ->first();

    //     if (!$hydrant) {
    //         return $this->jsonError('Hydrant tidak ditemukan atau tidak aktif', 404);
    //     }

    //     // Validasi hak akses user
    //     if (!$this->userCanCheckHydrant(Auth::user(), $hydrant)) {
    //         return $this->jsonError('Anda tidak memiliki akses untuk mengecek Hydrant ini', 403);
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'data' => [
    //             'hydrant' => $hydrant,
    //             'redirect_url' => route('hydrant-check.create', $hydrant->id),
    //         ]
    //     ]);
    // }
    public function validateHydrant(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string|max:255',
        ]);

        $barcode = $request->qr_code;

        $hydrant = Hydrant::with(['zone', 'building', 'user', 'floor', 'brand', 'hydrantType', 'extinguisherCondition'])
            ->where('is_active', true) // Perbaikan: gunakan true, bukan $true
            ->where(function ($query) use ($barcode) {
                $query->where('qr_code', $barcode)
                    ->orWhere('number_hydrant', $barcode);
            })
            ->first();

        if (!$hydrant) {
            $message = 'Hydrant dengan kode tersebut tidak ditemukan.';
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

        // Validasi akses user ke Hydrant ini
        if (!$this->userCanCheckHydrant($hydrant)) {
            $message = 'Anda tidak memiliki akses untuk mengecek Hydrant ini.';
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                ], 403);
            }

            return redirect()->route('hydrant-check.scan')
                ->with('error', $message);
        }

        // Set session untuk validasi di form create
        session(['scanned_hydrant_id' => $hydrant->id]);

        $redirectUrl = route('hydrant-check.create', $hydrant->id);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Hydrant ditemukan: ' . $hydrant->number_hydrant,
                'data' => [
                    'redirect_url' => $redirectUrl,
                ],
            ]);
        }

        // Redirect langsung ke form create
        return redirect()->route('hydrant-check.create', $hydrant->id);
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
     * Check if user can check this Hydrant (berdasarkan zona/group assignment)
     */
    private function userCanCheckHydrant(Hydrant $hydrant)
    {
        // Logic untuk cek apakah user boleh mengecek Hydrant ini
        // Contoh: berdasarkan zona yang ditugaskan ke user
        // Anda bisa menyesuaikan dengan business logic Anda

        // Contoh sederhana: user bisa mengecek Hydrant di zona yang sama dengan group user
        // return $user->group_id === $hydrant->group_id;
        // return true;
        return Auth::user()->group->zones->contains('id', $hydrant->zone_id);


        // Atau lebih kompleks: berdasarkan assignment khusus
        // return $user->zones->contains($hydrant->zone_id);
    }

    /**
     * Display Hydrant list that need to be checked by current user's group
     */
    // public function hydrantToCheck(Request $request)
    // {
    //     $user = Auth::user();

    //     if (!$user->group) {
    //         return redirect()->back()
    //             ->with('error', 'Anda tidak memiliki group assignment.');
    //     }

    //     // Dapatkan zona yang diassign ke group user
    //     $assignedZoneIds = $user->group->getAssignedZoneIds();

    //     if (empty($assignedZoneIds)) {
    //         return view('admin.hydrant-check.to-check', [
    //             'hydrants' => collect(),
    //             'assignedZones' => collect(),
    //             'filter' => $request->filter,
    //             'totalHydrants' => 0,
    //             'checkedCount' => 0,
    //             'uncheckedCount' => 0,
    //             'progress' => 0
    //         ])->with('info', 'Tidak ada zona yang ditugaskan ke group Anda.');
    //     }

    //     $currentMonth = now()->month;
    //     $currentYear = now()->year;

    //     // Dapatkan Hydrant yang sudah di-checklist bulan ini oleh SEMUA USER dalam GROUP YANG SAMA
    //     $checkedHydrantIds = HydrantCheck::whereHas('user', function ($query) use ($user) {
    //         $query->where('group_id', $user->group_id);
    //     })
    //         ->whereMonth('date_check', $currentMonth)
    //         ->whereYear('date_check', $currentYear)
    //         ->pluck('hydrant_id')
    //         ->toArray();

    //     // Dapatkan ID HydrantCheck terbaru untuk setiap Hydrant yang sudah dicek
    //     $latestHydrantCheckIds = [];
    //     if (!empty($checkedHydrantIds)) {
    //         $latestChecks = HydrantCheck::whereHas('user', function ($query) use ($user) {
    //             $query->where('group_id', $user->group_id);
    //         })
    //             ->whereMonth('date_check', $currentMonth)
    //             ->whereYear('date_check', $currentYear)
    //             ->whereIn('hydrant_id', $checkedHydrantIds)
    //             ->orderBy('hydrant_id')
    //             ->orderByDesc('id')
    //             ->get()
    //             ->groupBy('hydrant_id')
    //             ->map(function ($checks) {
    //                 return $checks->first()->id;
    //             });

    //         $latestHydrantCheckIds = $latestChecks->toArray();
    //     }

    //     // QUERY UNTUK SUMMARY (TERPISAH DARI PAGINATION)
    //     $summaryQuery = Hydrant::whereIn('zone_id', $assignedZoneIds)
    //         ->where('is_active', '1');

    //     $totalHydrants = $summaryQuery->count();
    //     $checkedCount = $summaryQuery->whereIn('id', $checkedHydrantIds)->count();
    //     $uncheckedCount = $totalHydrants - $checkedCount;
    //     $progress = $totalHydrants > 0 ? round(($checkedCount / $totalHydrants) * 100, 1) : 0;

    //     // QUERY UNTUK PAGINATION (FILTER BERDASARKAN REQUEST)
    //     $query = Hydrant::with(['zone', 'building', 'floor', 'hydrantType'])
    //         ->whereIn('zone_id', $assignedZoneIds)
    //         ->where('is_active', '1');

    //     // Filter berdasarkan status
    //     $filter = $request->filter;
    //     if ($filter === 'checked') {
    //         $query->whereIn('id', $checkedHydrantIds);
    //     } elseif ($filter === 'unchecked') {
    //         $query->whereNotIn('id', $checkedHydrantIds);
    //     }

    //     // Search
    //     if ($request->has('search') && !empty($request->search)) {
    //         $search = $request->search;
    //         $query->where(function ($q) use ($search) {
    //             $q->where('number_hydrant', 'like', "%{$search}%")
    //                 ->orWhere('location', 'like', "%{$search}%")
    //                 ->orWhereHas('zone', function ($q) use ($search) {
    //                     $q->where('name', 'like', "%{$search}%");
    //                 })
    //                 ->orWhereHas('building', function ($q) use ($search) {
    //                     $q->where('name', 'like', "%{$search}%");
    //                 });
    //         });
    //     }

    //     // Sorting
    //     $sort = $request->sort ?? 'zone_id';
    //     $direction = $request->direction ?? 'asc';

    //     $allowedSorts = ['number_hydrant', 'zone_id', 'location', 'building_id'];
    //     if (in_array($sort, $allowedSorts)) {
    //         $query->orderBy($sort, $direction);
    //     } else {
    //         $query->orderBy('zone_id')->orderBy('number_hydrant');
    //     }

    //     $hydrants = $query->paginate(10)->withQueryString();

    //     // Tambahkan status checked bulan ini dan ID HydrantCheck terbaru ke setiap Hydrant
    //     $hydrants->each(function ($hydrant) use ($checkedHydrantIds, $latestHydrantCheckIds) {
    //         $hydrant->is_checked_this_month = in_array($hydrant->id, $checkedHydrantIds);
    //         $hydrant->latest_check_id = $latestHydrantCheckIds[$hydrant->id] ?? null;
    //     });

    //     $assignedZones = Zone::whereIn('id', $assignedZoneIds)->get();

    //     return view('admin.hydrant-check.to-check', compact(
    //         'hydrants',
    //         'assignedZones',
    //         'filter',
    //         'sort',
    //         'direction',
    //         'totalHydrants',
    //         'checkedCount',
    //         'uncheckedCount',
    //         'progress'
    //     ));
    // }

    // =========================================== hydrant to check ===============================================
 
    public function hydrantToCheck(Request $request)
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
            return view('admin.hydrant-check.to-check', [
                'assignedZones' => collect(),
                'filter' => $request->filter,
                'totalHydrants' => 0,
                'checkedCount' => 0,
                'uncheckedCount' => 0,
                'progress' => 0
            ])->with('info', 'Tidak ada zona yang ditugaskan ke group Anda.');
        }

        // Jika request AJAX untuk Datatables
        if ($request->ajax()) {
            return $this->getHydrantData($request, $user, $assignedZoneIds);
        }

        // Untuk initial page load - hitung summary saja
        $summary = $this->getSummaryData($user, $assignedZoneIds, $request->filter);

        $assignedZones = Zone::whereIn('id', $assignedZoneIds)->get();

        return view('admin.hydrant-check.to-check', array_merge($summary, [
            'assignedZones' => $assignedZones,
            'filter' => $request->filter
        ]));
    }

    private function getHydrantData(Request $request, $user, $assignedZoneIds)
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        // Dapatkan Hydrant yang sudah di-checklist bulan ini
        $checkedHydrantIds = HydrantCheck::whereHas('user', function ($query) use ($user) {
                $query->where('group_id', $user->group_id);
            })
            ->whereMonth('date_check', $currentMonth)
            ->whereYear('date_check', $currentYear)
            ->pluck('hydrant_id')
            ->toArray();

        // Dapatkan ID HydrantCheck terbaru
        $latestHydrantCheckIds = [];
        if (!empty($checkedHydrantIds)) {
            $latestChecks = HydrantCheck::whereHas('user', function ($query) use ($user) {
                    $query->where('group_id', $user->group_id);
                })
                ->whereMonth('date_check', $currentMonth)
                ->whereYear('date_check', $currentYear)
                ->whereIn('hydrant_id', $checkedHydrantIds)
                ->orderBy('hydrant_id')
                ->orderByDesc('id')
                ->get()
                ->groupBy('hydrant_id')
                ->map(function ($checks) {
                    return $checks->first()->id;
                });
            
            $latestHydrantCheckIds = $latestChecks->toArray();
        }

        $query = Hydrant::with(['zone', 'building', 'floor', 'hydrantType'])
            ->whereIn('zone_id', $assignedZoneIds)
            ->where('is_active', '1');

        // Filter berdasarkan status
        $filter = $request->filter;
        if ($filter === 'checked') {
            $query->whereIn('id', $checkedHydrantIds);
        } elseif ($filter === 'unchecked') {
            $query->whereNotIn('id', $checkedHydrantIds);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('no', function($hydrant) {
                return '';
            })
            ->addColumn('status_badge', function($hydrant) use ($checkedHydrantIds) {
                $isChecked = in_array($hydrant->id, $checkedHydrantIds);
                if ($isChecked) {
                    return '<span class="badge bg-success"><i class="fas fa-check"></i> Sudah</span>';
                } else {
                    return '<span class="badge bg-warning"><i class="fas fa-times"></i> Belum</span>';
                }
            })
            ->addColumn('action', function($hydrant) use ($checkedHydrantIds, $latestHydrantCheckIds) {
                $isChecked = in_array($hydrant->id, $checkedHydrantIds);
                $latestCheckId = $latestHydrantCheckIds[$hydrant->id] ?? null;

                if ($isChecked && $latestCheckId) {
                    return '<a href="'.route('hydrant-check.edit', $latestCheckId).'" class="btn btn-info btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>';
                } else {
                    return '<a href="'.route('hydrant-check.create', $hydrant->id).'" class="btn btn-primary btn-sm">
                                <i class="fas fa-clipboard-check"></i> Checklist
                            </a>';
                }
            })
            ->addColumn('zone_name', function($hydrant) {
                return '<span class="badge bg-info">'.$hydrant->zone->name.'</span>';
            })
            ->addColumn('building_name', function($hydrant) {
                return $hydrant->building->name ?? '-';
            })
            ->addColumn('floor_name', function($hydrant) {
                return $hydrant->floor->name ?? '-';
            })
            ->addColumn('hydrant_type_name', function($hydrant) {
                return $hydrant->hydrantType->name ?? '-';
            })
            ->addColumn('status_info', function($hydrant) use ($checkedHydrantIds) {
                $isChecked = in_array($hydrant->id, $checkedHydrantIds);
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
            })
            ->rawColumns(['status_badge', 'action', 'zone_name', 'status_info'])
            ->make(true);
    }

    private function getSummaryData($user, $assignedZoneIds, $filter = null)
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        // Dapatkan Hydrant yang sudah di-checklist bulan ini
        $checkedHydrantIds = HydrantCheck::whereHas('user', function ($query) use ($user) {
                $query->where('group_id', $user->group_id);
            })
            ->whereMonth('date_check', $currentMonth)
            ->whereYear('date_check', $currentYear)
            ->pluck('hydrant_id')
            ->toArray();

        // Query untuk summary
        $summaryQuery = Hydrant::whereIn('zone_id', $assignedZoneIds)
            ->where('is_active', '1');

        $totalHydrants = $summaryQuery->count();
        $checkedCount = $summaryQuery->whereIn('id', $checkedHydrantIds)->count();
        $uncheckedCount = $totalHydrants - $checkedCount;
        $progress = $totalHydrants > 0 ? round(($checkedCount / $totalHydrants) * 100, 1) : 0;

        return [
            'totalHydrants' => $totalHydrants,
            'checkedCount' => $checkedCount,
            'uncheckedCount' => $uncheckedCount,
            'progress' => $progress
        ];
    }

    // =========================================== end hydrant to check ===============================================

}
