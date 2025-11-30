<?php

namespace App\Http\Controllers\Admin;

use App\Models\Apar;
use App\Models\Zone;
use App\Models\Group;
use App\Models\Hydrant;
use App\Models\AparCheck;
use App\Models\HydrantCheck;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardAdminController extends Controller
{
    public function dashboardSummary()
    {
        $user = Auth::user();
        
        // Untuk admin/superadmin, tampilkan data semua regu aktif
        if ($user->hasRole('superadmin') || $user->hasRole('admin')) {
            return $this->getAdminDashboardData();
        }

        // Untuk user biasa, gunakan logic sebelumnya
        if (!$user->group) {
            return $this->getEmptySummary();
        }

        $assignedZoneIds = $user->group->getAssignedZoneIds();

        if (empty($assignedZoneIds)) {
            return $this->getEmptySummary();
        }

        return $this->getUserDashboardData($user, $assignedZoneIds);
    }

    /**
     * Get dashboard data for admin/superadmin
     */
    private function getAdminDashboardData()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        // Total semua APAR dan Hydrant aktif
        $totalApars = Apar::where('is_active', true)->count();
        $totalHydrants = Hydrant::where('is_active', true)->count();
        $totalCombined = $totalApars + $totalHydrants;

        // APAR yang sudah dicek bulan ini (semua user)
        $checkedAparIds = AparCheck::whereMonth('date_check', $currentMonth)
            ->whereYear('date_check', $currentYear)
            ->pluck('apar_id')
            ->unique()
            ->toArray();

        $checkedAparCount = Apar::where('is_active', true)
            ->whereIn('id', $checkedAparIds)
            ->count();

        // Hydrant yang sudah dicek bulan ini (semua user)
        $checkedHydrantIds = HydrantCheck::whereMonth('date_check', $currentMonth)
            ->whereYear('date_check', $currentYear)
            ->pluck('hydrant_id')
            ->unique()
            ->toArray();

        $checkedHydrantCount = Hydrant::where('is_active', true)
            ->whereIn('id', $checkedHydrantIds)
            ->count();

        $checkedCombinedCount = $checkedAparCount + $checkedHydrantCount;
        $uncheckedAparCount = $totalApars - $checkedAparCount;
        $uncheckedHydrantCount = $totalHydrants - $checkedHydrantCount;
        $uncheckedCombinedCount = $totalCombined - $checkedCombinedCount;

        $aparProgress = $totalApars > 0 ? round(($checkedAparCount / $totalApars) * 100, 1) : 0;
        $hydrantProgress = $totalHydrants > 0 ? round(($checkedHydrantCount / $totalHydrants) * 100, 1) : 0;
        $progress = $totalCombined > 0 ? round(($checkedCombinedCount / $totalCombined) * 100, 1) : 0;

        return [
            'totalApars' => $totalApars,
            'totalHydrants' => $totalHydrants,
            'totalCombined' => $totalCombined,
            'checkedAparCount' => $checkedAparCount,
            'checkedHydrantCount' => $checkedHydrantCount,
            'checkedCombinedCount' => $checkedCombinedCount,
            'uncheckedAparCount' => $uncheckedAparCount,
            'uncheckedHydrantCount' => $uncheckedHydrantCount,
            'uncheckedCombinedCount' => $uncheckedCombinedCount,
            'progress' => $progress,
            'aparProgress' => $aparProgress,
            'hydrantProgress' => $hydrantProgress,
            'userType' => 'admin'
        ];
    }

    /**
     * Get dashboard data for regular user
     */
    private function getUserDashboardData($user, $assignedZoneIds)
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $totalApars = Apar::whereIn('zone_id', $assignedZoneIds)
            ->where('is_active', true)
            ->count();

        $totalHydrants = Hydrant::whereIn('zone_id', $assignedZoneIds)
            ->where('is_active', true)
            ->count();

        $totalCombined = $totalApars + $totalHydrants;

        $checkedAparIds = AparCheck::whereHas('user', function ($query) use ($user) {
            $query->where('group_id', $user->group_id);
        })
            ->whereMonth('date_check', $currentMonth)
            ->whereYear('date_check', $currentYear)
            ->pluck('apar_id')
            ->unique()
            ->toArray();

        $checkedAparCount = Apar::whereIn('zone_id', $assignedZoneIds)
            ->where('is_active', true)
            ->whereIn('id', $checkedAparIds)
            ->count();

        $checkedHydrantIds = HydrantCheck::whereHas('user', function ($query) use ($user) {
            $query->where('group_id', $user->group_id);
        })
            ->whereMonth('date_check', $currentMonth)
            ->whereYear('date_check', $currentYear)
            ->pluck('hydrant_id')
            ->unique()
            ->toArray();

        $checkedHydrantCount = Hydrant::whereIn('zone_id', $assignedZoneIds)
            ->where('is_active', true)
            ->whereIn('id', $checkedHydrantIds)
            ->count();

        $checkedCombinedCount = $checkedAparCount + $checkedHydrantCount;
        $uncheckedAparCount = $totalApars - $checkedAparCount;
        $uncheckedHydrantCount = $totalHydrants - $checkedHydrantCount;
        $uncheckedCombinedCount = $totalCombined - $checkedCombinedCount;

        $aparProgress = $totalApars > 0 ? round(($checkedAparCount / $totalApars) * 100, 1) : 0;
        $hydrantProgress = $totalHydrants > 0 ? round(($checkedHydrantCount / $totalHydrants) * 100, 1) : 0;
        $progress = $totalCombined > 0 ? round(($checkedCombinedCount / $totalCombined) * 100, 1) : 0;

        return [
            'totalApars' => $totalApars,
            'totalHydrants' => $totalHydrants,
            'totalCombined' => $totalCombined,
            'checkedAparCount' => $checkedAparCount,
            'checkedHydrantCount' => $checkedHydrantCount,
            'checkedCombinedCount' => $checkedCombinedCount,
            'uncheckedAparCount' => $uncheckedAparCount,
            'uncheckedHydrantCount' => $uncheckedHydrantCount,
            'uncheckedCombinedCount' => $uncheckedCombinedCount,
            'progress' => $progress,
            'aparProgress' => $aparProgress,
            'hydrantProgress' => $hydrantProgress,
            'userType' => 'user'
        ];
    }

    /**
     * Get empty summary data
     */
    private function getEmptySummary()
    {
        return [
            'totalApars' => 0,
            'totalHydrants' => 0,
            'totalCombined' => 0,
            'checkedAparCount' => 0,
            'checkedHydrantCount' => 0,
            'checkedCombinedCount' => 0,
            'uncheckedAparCount' => 0,
            'uncheckedHydrantCount' => 0,
            'uncheckedCombinedCount' => 0,
            'progress' => 0,
            'aparProgress' => 0,
            'hydrantProgress' => 0,
            'userType' => 'user'
        ];
    }

    /**
     * Get chart data for admin/superadmin
     */
    public function getChartData()
    {
        $user = Auth::user();
        
        $currentYear = now()->year;
        $labels = [];
        $aparData = [];
        $hydrantData = [];

        // Get data for last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $month = $date->month;
            $year = $date->year;
            $labels[] = $date->format('M Y');

            // For admin/superadmin, get all checks
            if ($user->hasRole('superadmin') || $user->hasRole('admin')) {
                $aparChecks = AparCheck::whereMonth('date_check', $month)
                    ->whereYear('date_check', $year)
                    ->select('apar_id')
                    ->distinct()
                    ->count('apar_id');

                $hydrantChecks = HydrantCheck::whereMonth('date_check', $month)
                    ->whereYear('date_check', $year)
                    ->select('hydrant_id')
                    ->distinct()
                    ->count('hydrant_id');
            } else {
                // For regular users, use existing logic
                if (!$user->group) {
                    $aparChecks = 0;
                    $hydrantChecks = 0;
                } else {
                    $assignedZoneIds = $user->group->getAssignedZoneIds();
                    
                    $aparChecks = AparCheck::whereHas('user', function ($query) use ($user) {
                        $query->where('group_id', $user->group_id);
                    })
                        ->whereMonth('date_check', $month)
                        ->whereYear('date_check', $year)
                        ->whereHas('apar', function ($query) use ($assignedZoneIds) {
                            $query->whereIn('zone_id', $assignedZoneIds)
                                ->where('is_active', true);
                        })
                        ->select('apar_id')
                        ->distinct()
                        ->count('apar_id');

                    $hydrantChecks = HydrantCheck::whereHas('user', function ($query) use ($user) {
                        $query->where('group_id', $user->group_id);
                    })
                        ->whereMonth('date_check', $month)
                        ->whereYear('date_check', $year)
                        ->whereHas('hydrant', function ($query) use ($assignedZoneIds) {
                            $query->whereIn('zone_id', $assignedZoneIds)
                                ->where('is_active', true);
                        })
                        ->select('hydrant_id')
                        ->distinct()
                        ->count('hydrant_id');
                }
            }

            $aparData[] = $aparChecks;
            $hydrantData[] = $hydrantChecks;
        }

        return [
            'labels' => $labels,
            'aparData' => $aparData,
            'hydrantData' => $hydrantData
        ];
    }

    /**
     * Get latest checks for admin/superadmin
     */
    public function getLatestChecks()
    {
        $user = Auth::user();

        // For admin/superadmin, get all latest checks
        if ($user->hasRole('superadmin') || $user->hasRole('admin')) {
            $latestAparChecks = AparCheck::with(['apar', 'user', 'apar.zone'])
                ->latest('date_check')
                ->take(10)
                ->get();

            $latestHydrantChecks = HydrantCheck::with(['hydrant', 'user', 'hydrant.zone'])
                ->latest('date_check')
                ->take(10)
                ->get();
        } else {
            // For regular users
            if (!$user->group) {
                return [
                    'latestAparChecks' => collect(),
                    'latestHydrantChecks' => collect()
                ];
            }

            $assignedZoneIds = $user->group->getAssignedZoneIds();
            
            $latestAparChecks = AparCheck::whereHas('user', function ($query) use ($user) {
                $query->where('group_id', $user->group_id);
            })
                ->whereHas('apar', function ($query) use ($assignedZoneIds) {
                    $query->whereIn('zone_id', $assignedZoneIds)
                        ->where('is_active', true);
                })
                ->with(['apar', 'user', 'apar.zone'])
                ->latest('date_check')
                ->take(5)
                ->get();

            $latestHydrantChecks = HydrantCheck::whereHas('user', function ($query) use ($user) {
                $query->where('group_id', $user->group_id);
            })
                ->whereHas('hydrant', function ($query) use ($assignedZoneIds) {
                    $query->whereIn('zone_id', $assignedZoneIds)
                        ->where('is_active', true);
                })
                ->with(['hydrant', 'user', 'hydrant.zone'])
                ->latest('date_check')
                ->take(5)
                ->get();
        }

        return [
            'latestAparChecks' => $latestAparChecks,
            'latestHydrantChecks' => $latestHydrantChecks
        ];
    }

    /**
    * Get group monitoring data for admin/superadmin
    */
    public function getGroupMonitoringData()
    {
        $user = Auth::user();
        
        if (!$user->hasRole('superadmin') && !$user->hasRole('admin')) {
            return [
                'groups' => collect(),
                'zones' => collect()
            ];
        }

        $currentMonth = now()->month;
        $currentYear = now()->year;

        // Get all groups with their progress (tanpa filter is_active untuk group dan users)
        $groups = Group::withCount(['users']) // Hapus where is_active
            ->with(['zones'])
            ->get()
            ->map(function ($group) use ($currentMonth, $currentYear) {
                $assignedZoneIds = $group->getAssignedZoneIds();
                
                $totalApars = Apar::whereIn('zone_id', $assignedZoneIds)
                    ->where('is_active', true)
                    ->count();
                    
                $totalHydrants = Hydrant::whereIn('zone_id', $assignedZoneIds)
                    ->where('is_active', true)
                    ->count();
                    
                $totalCombined = $totalApars + $totalHydrants;

                $checkedAparIds = AparCheck::whereHas('user', function ($query) use ($group) {
                    $query->where('group_id', $group->id);
                })
                    ->whereMonth('date_check', $currentMonth)
                    ->whereYear('date_check', $currentYear)
                    ->pluck('apar_id')
                    ->unique()
                    ->toArray();

                $checkedAparCount = Apar::whereIn('zone_id', $assignedZoneIds)
                    ->where('is_active', true)
                    ->whereIn('id', $checkedAparIds)
                    ->count();

                $checkedHydrantIds = HydrantCheck::whereHas('user', function ($query) use ($group) {
                    $query->where('group_id', $group->id);
                })
                    ->whereMonth('date_check', $currentMonth)
                    ->whereYear('date_check', $currentYear)
                    ->pluck('hydrant_id')
                    ->unique()
                    ->toArray();

                $checkedHydrantCount = Hydrant::whereIn('zone_id', $assignedZoneIds)
                    ->where('is_active', true)
                    ->whereIn('id', $checkedHydrantIds)
                    ->count();

                $checkedCombinedCount = $checkedAparCount + $checkedHydrantCount;
                $progress = $totalCombined > 0 ? round(($checkedCombinedCount / $totalCombined) * 100, 1) : 0;

                return [
                    'id' => $group->id,
                    'name' => $group->name,
                    'user_count' => $group->users_count,
                    'zones' => $group->zones,
                    'total_apar' => $totalApars,
                    'total_hydrant' => $totalHydrants,
                    'total_combined' => $totalCombined,
                    'checked_apar' => $checkedAparCount,
                    'checked_hydrant' => $checkedHydrantCount,
                    'checked_combined' => $checkedCombinedCount,
                    'progress' => $progress,
                    'apar_progress' => $totalApars > 0 ? round(($checkedAparCount / $totalApars) * 100, 1) : 0,
                    'hydrant_progress' => $totalHydrants > 0 ? round(($checkedHydrantCount / $totalHydrants) * 100, 1) : 0
                ];
            });

        // Get all zones with their assignment status
        $zones = Zone::with(['groups'])->get();

        return [
            'groups' => $groups,
            'zones' => $zones
        ];
    }

    public function index()
    {
        $user = Auth::user();
        
        // Ambil data summary
        $summary = $this->dashboardSummary();

        // Ambil data assigned zones (untuk user biasa)
        $assignedZones = collect();
        if (!$user->hasRole('superadmin') && !$user->hasRole('admin') && $user->group) {
            $assignedZoneIds = $user->group->getAssignedZoneIds();
            if (!empty($assignedZoneIds)) {
                $assignedZones = Zone::whereIn('id', $assignedZoneIds)->get();
            }
        }

        // Ambil data untuk chart
        $chartData = $this->getChartData();

        // Ambil data latest checks
        $latestChecks = $this->getLatestChecks();

        // Ambil data monitoring group (hanya untuk admin/superadmin)
        $monitoringData = $this->getGroupMonitoringData();

        return view('admin.dashboard-admin.index', compact(
            'summary', 
            'assignedZones', 
            'chartData', 
            'latestChecks',
            'monitoringData'
        ));
    }
}
