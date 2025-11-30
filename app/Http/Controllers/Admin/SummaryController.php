<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apar;
use App\Models\AparCheck;
use App\Models\AparType;
use App\Models\Brand;
use App\Models\Building;
use App\Models\ExtinguisherCondition;
use App\Models\Floor;
use App\Models\Group;
use App\Models\Hydrant;
use App\Models\HydrantCheck;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SummaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexApar(Request $request)
    {
        if ($request->ajax()) {
            return $this->getAparChecksData($request);
        }

        // Get filter data
        $apars = Apar::where('is_active', true)->orderBy('number_apar')->get();
        $zones = Zone::orderBy('name')->get();
        $buildings = Building::orderBy('name')->get();
        $groups = Group::orderBy('name')->get();
        $users = User::orderBy('name')->get();
        $conditions = ExtinguisherCondition::orderBy('name')->get();
        $floors = Floor::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        $aparTypes = AparType::orderBy('name')->get();

        return view('admin.summary.apar', compact(
            'apars', 'zones', 'buildings', 'groups', 'users', 'conditions', 'floors', 'brands', 'aparTypes', 
        ));
    }

     /**
     * Datatable server-side processing
     */
    public function getAparChecksData(Request $request)
    {
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
                return '
                    <div class="d-inline-flex align-items-center gap-1">
                        <a href="' . route('apar-check.show', $check->id) . '" class="btn btn-sm btn-danger" title="View Detail">
                            <i class="fas fa-eye text-info"></i>
                        </a>
                        <a href="' . route('apar-check.edit', $check->id) . '" class="btn btn-sm btn-light" style="border: 1px solid rgba(0,0,0,0.15)" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete(' . $check->id . ')" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                        <form action="' . route('apar-check.destroy', $check->id) . '" id="delete-form-' . $check->id . '" method="post" class="d-none">
                            ' . csrf_field() . method_field('DELETE') . '
                        </form>
                    </div>
                ';
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
     * Display a listing of the resource.
     */
    public function indexHydrant(Request $request)
    {
        if ($request->ajax()) {
            return $this->getHydrantChecksData($request);
        }

        // Get filter data
        $hydrants = Hydrant::where('is_active', true)->orderBy('number_hydrant')->get();
        $zones = Zone::orderBy('name')->get();
        $buildings = Building::orderBy('name')->get();
        $groups = Group::orderBy('name')->get();
        $users = User::orderBy('name')->get();
        $conditions = ExtinguisherCondition::orderBy('name')->get();

        return view('admin.summary.hydrant', compact(
            'hydrants', 'zones', 'buildings', 'groups', 'users', 'conditions'
        ));
    }

     /**
     * Datatable server-side processing
     */
    public function getHydrantChecksData(Request $request)
    {
        $query = HydrantCheck::with([
            'user',
            'hydrant',
            'zone', 
            'building',
            'group',
            'extinguisherCondition',
            'hydrantDoor',
            'hydrantCoupling',
            'hydrantMainValve', 
            'hydrantHose',
            'hydrantNozzle',
            'hydrantSafetyMarking',
            'hydrantGuide'
        ])->latest();

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->addColumn('hydrant_number', function($check) {
                return $check->hydrant ? $check->hydrant->number_hydrant : '-';
            })
            ->addColumn('hydrant_location', function($check) {
                return $check->hydrant ? $check->hydrant->location : $check->location;
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
            ->addColumn('hydrant_door', function($check) {
                return $check->hydrantDoor ? $check->hydrantDoor->name : '-';
            })
            ->addColumn('hydrant_coupling', function($check) {
                return $check->hydrantCoupling ? $check->hydrantCoupling->name : '-';
            })
            ->addColumn('hydrant_main_valve', function($check) {
                return $check->hydrantMainValve ? $check->hydrantMainValve->name : '-';
            })
            ->addColumn('hydrant_hose', function($check) {
                return $check->hydrantHose ? $check->hydrantHose->name : '-';
            })
            ->addColumn('hydrant_nozzle', function($check) {
                return $check->hydrantNozzle ? $check->hydrantNozzle->name : '-';
            })
            ->addColumn('hydrant_safety_marking', function($check) {
                return $check->hydrantSafetyMarking ? $check->hydrantSafetyMarking->name : '-';
            })
            ->addColumn('hydrant_guide', function($check) {
                return $check->hydrantGuide ? $check->hydrantGuide->name : '-';
            })
            ->addColumn('condition_badge', function($check) {
                if (!$check->condition) return '-';
                
                $badgeClass = 'bg-secondary';
                if ($check->condition->name == 'Normal') $badgeClass = 'bg-success';
                if ($check->condition->name == 'Rusak') $badgeClass = 'bg-danger';
                if ($check->condition->name == 'Perlu Perhatian') $badgeClass = 'bg-warning';
                
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
                return '
                    <div class="d-inline-flex align-items-center gap-1">
                        <a href="' . route('hydrant-check.show', $check->id) . '" class="btn btn-sm btn-info" title="View Detail">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="' . route('hydrant-check.edit', $check->id) . '" class="btn btn-sm btn-light" style="border: 1px solid rgba(0,0,0,0.15)" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete(' . $check->id . ')" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                        <form action="' . route('hydrant-check.destroy', $check->id) . '" id="delete-form-' . $check->id . '" method="post" class="d-none">
                            ' . csrf_field() . method_field('DELETE') . '
                        </form>
                    </div>
                ';
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
                          ->orWhereHas('hydrant', function($q) use ($searchValue) {
                              $q->where('number_hydrant', 'like', "%$searchValue%");
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
}
