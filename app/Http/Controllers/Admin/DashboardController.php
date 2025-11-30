<?php

namespace App\Http\Controllers\Admin;

use App\Models\Apar;
use App\Models\Zone;
use App\Models\AparCheck;
use App\Models\Hydrant;
use App\Models\HydrantCheck;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboardSummary()
    {
        $user = Auth::user();

        if (!$user->group) {
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
                'hydrantProgress' => 0
            ];
        }

        // Dapatkan zona yang diassign ke group user
        $assignedZoneIds = $user->group->getAssignedZoneIds();

        if (empty($assignedZoneIds)) {
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
                'hydrantProgress' => 0
            ];
        }

        $currentMonth = now()->month;
        $currentYear = now()->year;

        // Total APAR aktif di zona yang ditugaskan
        $totalApars = Apar::whereIn('zone_id', $assignedZoneIds)
            ->where('is_active', true)
            ->count();

        // Total Hydrant aktif di zona yang ditugaskan
        $totalHydrants = Hydrant::whereIn('zone_id', $assignedZoneIds)
            ->where('is_active', true)
            ->count();

        $totalCombined = $totalApars + $totalHydrants;

        // APAR yang sudah dicek bulan ini oleh user dalam group yang sama
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

        // Hydrant yang sudah dicek bulan ini oleh user dalam group yang sama
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
            'hydrantProgress' => $hydrantProgress
        ];
    }

    public function getChartData()
    {
        $user = Auth::user();
        
        if (!$user->group) {
            return [
                'labels' => [],
                'aparData' => [],
                'hydrantData' => []
            ];
        }

        $assignedZoneIds = $user->group->getAssignedZoneIds();
        
        if (empty($assignedZoneIds)) {
            return [
                'labels' => [],
                'aparData' => [],
                'hydrantData' => []
            ];
        }

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

            // APAR checks count for this month (unique apar_id)
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

            $aparData[] = $aparChecks;

            // Hydrant checks count for this month (unique hydrant_id)
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

            $hydrantData[] = $hydrantChecks;
        }

        return [
            'labels' => $labels,
            'aparData' => $aparData,
            'hydrantData' => $hydrantData
        ];
    }

    public function getLatestChecks()
    {
        $user = Auth::user();
        
        if (!$user->group) {
            return [
                'latestAparChecks' => collect(),
                'latestHydrantChecks' => collect()
            ];
        }

        $assignedZoneIds = $user->group->getAssignedZoneIds();
        
        if (empty($assignedZoneIds)) {
            return [
                'latestAparChecks' => collect(),
                'latestHydrantChecks' => collect()
            ];
        }

        // Get latest 5 APAR checks
        $latestAparChecks = AparCheck::whereHas('user', function ($query) use ($user) {
            $query->where('group_id', $user->group_id);
        })
            ->whereHas('apar', function ($query) use ($assignedZoneIds) {
                $query->whereIn('zone_id', $assignedZoneIds)
                    ->where('is_active', true);
            })
            ->with(['apar', 'user', 'zone'])
            ->latest('date_check')
            ->take(5)
            ->get();

        // Get latest 5 Hydrant checks
        $latestHydrantChecks = HydrantCheck::whereHas('user', function ($query) use ($user) {
            $query->where('group_id', $user->group_id);
        })
            ->whereHas('hydrant', function ($query) use ($assignedZoneIds) {
                $query->whereIn('zone_id', $assignedZoneIds)
                    ->where('is_active', true);
            })
            ->with(['hydrant', 'user', 'zone'])
            ->latest('date_check')
            ->take(5)
            ->get();

        return [
            'latestAparChecks' => $latestAparChecks,
            'latestHydrantChecks' => $latestHydrantChecks
        ];
    }

    public function index()
    {
        $user = Auth::user();
        // Ambil data summary
        $summary = $this->dashboardSummary();

        // Ambil data assigned zones
        $assignedZones = collect(); // default empty collection

        if ($user->group) {
            $assignedZoneIds = $user->group->getAssignedZoneIds();
            if (!empty($assignedZoneIds)) {
                $assignedZones = Zone::whereIn('id', $assignedZoneIds)->get();
            }
        }

        // Ambil data untuk chart
        $chartData = $this->getChartData();

        // Ambil data latest checks
        $latestChecks = $this->getLatestChecks();

        return view('admin.dashboard.index', compact('summary', 'assignedZones', 'chartData', 'latestChecks'));
    }
}
