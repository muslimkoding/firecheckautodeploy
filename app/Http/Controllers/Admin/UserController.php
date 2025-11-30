<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\Apar;
use App\Models\AparCheck;
use App\Models\Competency;
use App\Models\EmployeeType;
use App\Models\Group;
use App\Models\Hydrant;
use App\Models\HydrantCheck;
use App\Models\Position;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Models\Role;
use SweetAlert2\Laravel\Swal;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller implements HasMiddleware
{
    /**
     * role & permission
     */
    public static function middleware()
    {
        return [
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('user.view'), only:['index', 'getUserData']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('user.create'), only:['create', 'store']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('user.update'), only:['update', 'edit']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('user.destroy'), only:['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->getUserData($request);
        }

        // get filter data
        $employeeTypes = EmployeeType::orderBy('name')->get();
        $groups = Group::orderBy('name')->get();
        $positions = Position::orderBy('name')->get();
        $competencies = Competency::orderBy('name')->get();

        return view('admin.user.index', compact('employeeTypes', 'groups', 'positions', 'competencies'));
    }

    /**
     * Datatable server-side processing
     */
    public function getUserData(Request $request)
    {
        $query = User::with('employeeType', 'group', 'position', 'competency', 'roles')->latest()->select('users.*');

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('group', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('competency', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->addColumn('avatar_display', function($user) {
                if ($user->image) {
                    return '<div class="text-center">
                                <img src="' . asset('storage/' . $user->image) . '" 
                                     alt="' . $user->name . '" 
                                     class="rounded-circle" 
                                     width="40" height="40"
                                     style="object-fit: cover;">
                            </div>';
                }
                return '<div class="text-center">
                            <div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center" 
                                 style="width: 40px; height: 40px;">
                                <i class="fas fa-user text-white"></i>
                            </div>
                        </div>';
            })
            ->addColumn('employee_type_name', function($user) {
                return $user->employeeType ? $user->employeeType->name : '-';
            })
            ->addColumn('group_name', function($user) {
                return $user->group ? $user->group->name : '-';
            })
            ->addColumn('position_name', function($user) {
                return $user->position ? $user->position->name : '-';
            })
            ->addColumn('competency_name', function($user) {
                return $user->competency ? $user->competency->name : '-';
            })
            ->addColumn('formatted_date_birth', function($user) {
                return $user->formatted_date_birth ?? '-';
            })
            ->addColumn('email_status', function($user) {
                return $user->email_verified_at 
                    ? '<span class="badge bg-success">Terverifikasi</span>'
                    : '<span class="badge bg-warning">Belum Verifikasi</span>';
            })
            ->addColumn('roles_display', function($user) {
                return $user->roles->isEmpty() 
                    ? '<span class="text-muted">-</span>' 
                    : $user->roles->pluck('name')->implode(', ');
            })
            // ->addColumn('action', function ($user) {
            //     return '
            //         <div class="d-inline-flex align-items-center gap-1">
            //             <a href="' . route('user.edit', $user->id) . '" class="btn btn-sm btn-light" style="border: 1px solid rgba(0,0,0,0.15)">
            //                 <i class="fa-solid fa-pen-to-square"></i>
            //             </a>
            //             <a href="' . route('user.show', $user->id) . '" class="btn btn-sm btn-info">
            //                 <i class="fa-solid fa-eye"></i>
            //             </a>
            //             <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete(' . $user->id . ')">
            //                 <i class="fa-solid fa-trash-can"></i>
            //             </button>
            //             <form action="' . route('user.destroy', $user->id) . '" id="delete-form-' . $user->id . '" method="post" class="d-none">
            //                 ' . csrf_field() . method_field('DELETE') . '
            //             </form>
            //         </div>
            //     ';
            // })
            ->addColumn('action', function ($user) {
                $html = '<div class="d-inline-flex align-items-center gap-1">';
                
                // Edit button dengan permission check
                if (auth()->user()->can('user.update')) {
                    $html .= '
                        <a href="' . route('user.edit', $user->id) . '" class="btn btn-sm btn-light" style="border: 1px solid rgba(0,0,0,0.15)">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                    ';
                }
                
                // Show button (biasanya selalu visible)
                $html .= '
                    <a href="' . route('user.show', $user->id) . '" class="btn btn-sm btn-info">
                        <i class="fa-solid fa-eye"></i>
                    </a>
                ';
                
                // Delete button dengan permission check
                if (auth()->user()->can('user.destroy')) {
                    $html .= '
                        <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete(' . $user->id . ')">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                        <form action="' . route('user.destroy', $user->id) . '" id="delete-form-' . $user->id . '" method="post" class="d-none">
                            ' . csrf_field() . method_field('DELETE') . '
                        </form>
                    ';
                }
                
                $html .= '</div>';
                
                return $html;
            })
            
            ->filter(function ($query) use ($request) {
                if ($request->filled('search.value')) {
                    $search = $request->input('search.value');
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%")
                          ->orWhere('email', 'like', "%$search%")
                          ->orWhere('nip', 'like', "%$search%")
                          ->orWhereHas('employeeType', function($q) use ($search) {
                              $q->where('name', 'like', "%$search%");
                          })
                          ->orWhereHas('group', function($q) use ($search) {
                              $q->where('name', 'like', "%$search%");
                          })
                          ->orWhereHas('position', function($q) use ($search) {
                              $q->where('name', 'like', "%$search%");
                          })
                          ->orWhereHas('roles', function($q) use ($search) {
                            $q->where('name', 'like', "%$search%");
                        });
                    });
                }

                if ($request->filled('employee_type_id')) {
                    $query->where('employe_type_id', $request->employee_type_id);
                }

                if ($request->filled('group_id')) {
                    $query->where('group_id', $request->group_id);
                }

                if ($request->filled('position_id')) {
                    $query->where('position_id', $request->position_id);
                }

                if ($request->filled('competency_id')) {
                    $query->where('competency_id', $request->competency_id);
                }

                // Filter email verification status
                if ($request->filled('email_verified')) {
                    if ($request->email_verified === 'verified') {
                        $query->whereNotNull('email_verified_at');
                    } elseif ($request->email_verified === 'unverified') {
                        $query->whereNull('email_verified_at');
                    }
                }
            })
            ->rawColumns(['avatar_display', 'email_status', 'action', 'roles_display'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employeeTypes = EmployeeType::all();
        $groups = Group::all();
        $positions = Position::all();
        $competencies = Competency::all();
        $roles = Role::pluck('name', 'name')->all();
        
        $lastUser = User::latest()->first();
        
        return view('admin.user.create', compact(
            'employeeTypes', 
            'groups', 
            'positions', 
            'competencies',
            'lastUser',
            'roles'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        try {
            $validated = $request->validated();

            // Handle image upload
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('users', 'public');
                $validated['image'] = $imagePath;
            }

            // Hash password
            $validated['password'] = Hash::make($validated['password']);

            // Create user
            $user = User::create($validated);
            $user->syncRoles($request->roles);

            Log::info('User created successfully', ['user_id' => $user->id, 'email' => $user->email]);

            Swal::success([
                'title' => 'User berhasil ditambahkan',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('user.index');
                // ->with('success', 'User berhasil ditambahkan.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('User creation validation failed', ['errors' => $e->errors()]);
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
                
        } catch (\Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            Swal::error([
                'title' => 'Terjadi kesalahan saat menambahkan user: ' . $e->getMessage(),
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menambahkan user: ' . $e->getMessage())
                ->withInput();
        }
    }

     /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $employeeTypes = EmployeeType::all();
        $groups = Group::all();
        $positions = Position::all();
        $competencies = Competency::all();
        
        // Get user statistics
        $userStats = $this->getUserStatistics($user);
    
        return view('admin.user.show', compact(
            'user',
            'employeeTypes', 
            'groups', 
            'positions', 
            'competencies',
            'userStats'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $employeeTypes = EmployeeType::all();
            $groups = Group::all();
            $positions = Position::all();
            $competencies = Competency::all();

            $roles = Role::pluck('name', 'name')->all();
            $userRoles = $user->roles->pluck('name', 'name')->all();
            
            $lastUser = User::where('id', '!=', $user->id)->latest()->first();
            
            return view('admin.user.edit', compact(
                'user',
                'employeeTypes', 
                'groups', 
                'positions', 
                'competencies',
                'lastUser',
                'roles',
                'userRoles'
            ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            $validated = $request->validated();

            // Handle image removal
            if ($request->has('remove_image') && $user->image) {
                // Delete old image
                Storage::disk('public')->delete($user->image);
                $validated['image'] = null;
            }

            // Handle new image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($user->image) {
                    Storage::disk('public')->delete($user->image);
                }
                
                $imagePath = $request->file('image')->store('users', 'public');
                $validated['image'] = $imagePath;
            }

            // Handle password update
            if ($request->filled('password')) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }

            // Remove remove_image from validated data as it's not a database field
            unset($validated['remove_image']);

            // Update user
            $user->update($validated);

            $user->syncRoles($request->roles);

            Log::info('User updated successfully', [
                'user_id' => $user->id, 
                'email' => $user->email,
                'updated_fields' => array_keys($validated)
            ]);

            Swal::success([
                'title' => 'User berhasil diperbarui',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);

            return redirect()->route('user.index');
                // ->with('success', 'User berhasil diperbarui.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('User update validation failed', [
                'user_id' => $user->id,
                'errors' => $e->errors()
            ]);
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
                
        } catch (\Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui user: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            // Hapus gambar jika ada
            if ($user->image && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }
    
            // Hapus user
            $user->delete();

            Swal::success([
                'title' => 'User berhasil dihapus',
                'showConfirmButton' => false,
                'timer' => 2000
            ]);
    
            return redirect()->back();
        } catch (\Exception $e) {
            Log::error('User gagal dihapus : ' . $e->getMessage());

            Swal::error([
                'title' => 'User gagal dihapus!',
                'showConfirmButton' => false,
                'timer' => 2000,
            ]);
            
            return redirect()->back();
        }
    }

    /**
     * Get user statistics for checklist activities
     */
    private function getUserStatistics(User $user)
    {
        $currentYear = now()->year;
        
        // Total APAR checks (all time)
        $totalAparChecks = AparCheck::where('user_id', $user->id)->count();
        
        // Total Hydrant checks (all time)
        $totalHydrantChecks = HydrantCheck::where('user_id', $user->id)->count();
        
        // Total checklist (APAR + Hydrant)
        $totalChecklists = $totalAparChecks + $totalHydrantChecks;
        
        // Unique APAR checked (count distinct apar_id)
        $uniqueAparChecked = AparCheck::where('user_id', $user->id)
            ->distinct('apar_id')
            ->count('apar_id');
            
        // Unique Hydrant checked (count distinct hydrant_id)
        $uniqueHydrantChecked = HydrantCheck::where('user_id', $user->id)
            ->distinct('hydrant_id')
            ->count('hydrant_id');
        
        // Current month statistics
        $currentMonth = now()->month;
        $currentYear = now()->year;
        
        $aparChecksThisMonth = AparCheck::where('user_id', $user->id)
            ->whereMonth('date_check', $currentMonth)
            ->whereYear('date_check', $currentYear)
            ->count();
            
        $hydrantChecksThisMonth = HydrantCheck::where('user_id', $user->id)
            ->whereMonth('date_check', $currentMonth)
            ->whereYear('date_check', $currentYear)
            ->count();
            
        $totalChecksThisMonth = $aparChecksThisMonth + $hydrantChecksThisMonth;
        
        // Last activity
        $lastAparCheck = AparCheck::where('user_id', $user->id)
            ->latest('date_check')
            ->first();
            
        $lastHydrantCheck = HydrantCheck::where('user_id', $user->id)
            ->latest('date_check')
            ->first();
            
        $lastActivity = collect([$lastAparCheck, $lastHydrantCheck])
            ->filter()
            ->sortByDesc('date_check')
            ->first();

        // Monthly data for chart (last 6 months)
        $monthlyData = $this->getMonthlyChecklistData($user);

        return [
            // Main statistics
            'total_checklists' => $totalChecklists,
            'total_apar_checked' => $uniqueAparChecked,
            'total_hydrant_checked' => $uniqueHydrantChecked,
            
            // Current month
            'apar_checks_this_month' => $aparChecksThisMonth,
            'hydrant_checks_this_month' => $hydrantChecksThisMonth,
            'total_checks_this_month' => $totalChecksThisMonth,
            
            // All time counts
            'total_apar_checks' => $totalAparChecks,
            'total_hydrant_checks' => $totalHydrantChecks,
            
            // Activity info
            'last_activity' => $lastActivity,
            'last_apar_check' => $lastAparCheck,
            'last_hydrant_check' => $lastHydrantCheck,
            
            // Monthly data for charts
            'monthly_data' => $monthlyData,
            
            // Performance metrics
            'average_checks_per_month' => $this->getAverageChecksPerMonth($user),
            'completion_rate' => $this->getCompletionRate($user),
        ];
    }

    /**
     * Get monthly checklist data for charts
     */
    private function getMonthlyChecklistData(User $user)
    {
        $monthlyData = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $month = $date->month;
            $year = $date->year;
            $monthName = $date->format('M Y');
            
            $aparChecks = AparCheck::where('user_id', $user->id)
                ->whereMonth('date_check', $month)
                ->whereYear('date_check', $year)
                ->count();
                
            $hydrantChecks = HydrantCheck::where('user_id', $user->id)
                ->whereMonth('date_check', $month)
                ->whereYear('date_check', $year)
                ->count();
                
            $monthlyData[] = [
                'month' => $monthName,
                'apar_checks' => $aparChecks,
                'hydrant_checks' => $hydrantChecks,
                'total_checks' => $aparChecks + $hydrantChecks,
            ];
        }
        
        return $monthlyData;
    }

    /**
     * Calculate average checks per month
     */
    private function getAverageChecksPerMonth(User $user)
    {
        $firstCheck = AparCheck::where('user_id', $user->id)
            ->orWhere(function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->oldest('date_check')
            ->first();
            
        if (!$firstCheck) {
            return 0;
        }
        
        $monthsActive = now()->diffInMonths($firstCheck->date_check) ?: 1;
        $totalChecks = AparCheck::where('user_id', $user->id)->count() + 
                      HydrantCheck::where('user_id', $user->id)->count();
        
        return round($totalChecks / $monthsActive, 1);
    }

    /**
     * Calculate completion rate based on assigned zones
     */
    private function getCompletionRate(User $user)
    {
        if (!$user->group) {
            return 0;
        }
        
        $assignedZoneIds = $user->group->getAssignedZoneIds();
        
        if (empty($assignedZoneIds)) {
            return 0;
        }
        
        $currentMonth = now()->month;
        $currentYear = now()->year;
        
        // Total APAR in assigned zones
        $totalApars = \App\Models\Apar::whereIn('zone_id', $assignedZoneIds)
            ->where('is_active', true)
            ->count();
            
        // Total Hydrant in assigned zones
        $totalHydrants = \App\Models\Hydrant::whereIn('zone_id', $assignedZoneIds)
            ->where('is_active', true)
            ->count();
            
        $totalEquipment = $totalApars + $totalHydrants;
        
        if ($totalEquipment === 0) {
            return 0;
        }
        
        // APAR checked by this user this month
        $aparChecked = AparCheck::where('user_id', $user->id)
            ->whereMonth('date_check', $currentMonth)
            ->whereYear('date_check', $currentYear)
            ->whereHas('apar', function($query) use ($assignedZoneIds) {
                $query->whereIn('zone_id', $assignedZoneIds);
            })
            ->distinct('apar_id')
            ->count('apar_id');
            
        // Hydrant checked by this user this month
        $hydrantChecked = HydrantCheck::where('user_id', $user->id)
            ->whereMonth('date_check', $currentMonth)
            ->whereYear('date_check', $currentYear)
            ->whereHas('hydrant', function($query) use ($assignedZoneIds) {
                $query->whereIn('zone_id', $assignedZoneIds);
            })
            ->distinct('hydrant_id')
            ->count('hydrant_id');
            
        $totalChecked = $aparChecked + $hydrantChecked;
        
        return round(($totalChecked / $totalEquipment) * 100, 1);
    }
}
