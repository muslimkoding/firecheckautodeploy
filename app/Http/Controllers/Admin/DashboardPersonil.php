<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Competency;
use App\Models\EmployeeType;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardPersonil extends Controller
{
    public function index()
    {
        // A. Summary Cards
        $summary = [
            // 'totalUsers' => User::count(),
            'totalUsers' => User::whereNotNull('group_id')->count(),
            'operationChief' => User::whereHas('position', function($query) {
                $query->where('name', 'Airport Rescue & Fire Fighting Operation Chief');
            })->count(),
            'chiefAssistant' => User::whereHas('position', function($query) {
                $query->where('name', 'Airport Rescue & Fire Fighting Operation Chief Assistant');
            })->count(),
            'officer' => User::whereHas('position', function($query) {
                $query->whereIn('name', [
                    'Airport Rescue & Fire Fighting Operation Senior Firefighter',
                    'Airport Rescue & Fire Fighting Operation Junior Firefighter',
                    'Airport Rescue & Fire Fighting Operation Basic Firefighter'
                ]);
            })->count(),
        ];

        // B. Chart Komposisi Kompetensi
        $competencyChart = $this->getCompetencyChartData();

        // C. Table Department Head
        $departmentHeads = User::with(['position', 'competency', 'employeeType'])
            ->whereHas('position', function($query) {
                $query->where('name', 'Airport Rescue & Fire Fighting Operation Department Head');
            })
            ->select('name', 'nip', 'position_id', 'employe_type_id', 'competency_id')
            ->get()
            ->map(function($user) {
                return [
                    'name' => $user->name,
                    'nip' => $user->nip,
                    'position' => $user->position->name ?? '-',
                    'employee_type' => $user->employeeType->name ?? '-',
                    'competency' => $user->competency->name ?? '-',
                ];
            });

        // D. Table Operation Chief dengan Group
        $operationChiefs = User::with(['position', 'group'])
            ->whereHas('position', function($query) {
                $query->where('name', 'Airport Rescue & Fire Fighting Operation Chief');
            })
            ->select('name', 'nip', 'position_id', 'group_id')
            ->get()
            ->map(function($user) {
                return [
                    'name' => $user->name,
                    'nip' => $user->nip,
                    'position' => $user->position->name ?? '-',
                    'group' => $user->group->name ?? '-',
                ];
            });

        // E. Table Group Personil
        $groupPersonnel = Group::withCount('users')
            ->get()
            ->map(function($group) {
                return [
                    'group_name' => $group->name,
                    'total_personnel' => $group->users_count,
                ];
            });

        // F. Table Competency dengan Persentase
        $totalUsers = $summary['totalUsers'];
        $competencyPersonnel = Competency::withCount('users')
            ->get()
            ->map(function($competency) use ($totalUsers) {
                $percentage = $totalUsers > 0 ? round(($competency->users_count / $totalUsers) * 100, 2) : 0;
                return [
                    'competency_name' => $competency->name,
                    'total_personnel' => $competency->users_count,
                    'percentage' => $percentage,
                ];
            });

        // G. Table Employee Type
        $employeeTypePersonnel = EmployeeType::withCount('users')
            ->get()
            ->map(function($employeeType) {
                return [
                    'employee_type_name' => $employeeType->name,
                    'total_personnel' => $employeeType->users_count,
                ];
            });

        // H. Top 5 Pengecekan Terbanyak (Bulan Ini)
        $topCheckers = $this->getTopCheckers();

        return view('admin.dashboard-personil.index', compact(
            'summary',
            'competencyChart',
            'departmentHeads',
            'operationChiefs',
            'groupPersonnel',
            'competencyPersonnel',
            'employeeTypePersonnel',
            'topCheckers'
        ));
    }

    /**
     * Get data untuk chart komposisi kompetensi
     */
    private function getCompetencyChartData()
    {
        $competencies = Competency::withCount('users')->get();
        
        $labels = $competencies->pluck('name')->toArray();
        $data = $competencies->pluck('users_count')->toArray();

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    /**
 * Get top 5 personil dengan pengecekan terbanyak bulan ini (Manual Query)
 */
private function getTopCheckers()
{
    $currentMonth = now()->month;
    $currentYear = now()->year;

    try {
        $topCheckers = User::select([
                'users.id',
                'users.name',
                'users.nip',
                DB::raw('(SELECT COUNT(*) FROM apar_checks WHERE apar_checks.user_id = users.id AND MONTH(apar_checks.created_at) = ' . $currentMonth . ' AND YEAR(apar_checks.created_at) = ' . $currentYear . ') as apar_checks_count'),
                DB::raw('(SELECT COUNT(*) FROM hydrant_checks WHERE hydrant_checks.user_id = users.id AND MONTH(hydrant_checks.created_at) = ' . $currentMonth . ' AND YEAR(hydrant_checks.created_at) = ' . $currentYear . ') as hydrant_checks_count')
            ])
            ->where(function($query) use ($currentMonth, $currentYear) {
                $query->whereExists(function($subQuery) use ($currentMonth, $currentYear) {
                    $subQuery->select(DB::raw(1))
                            ->from('apar_checks')
                            ->whereRaw('apar_checks.user_id = users.id')
                            ->whereMonth('apar_checks.created_at', $currentMonth)
                            ->whereYear('apar_checks.created_at', $currentYear);
                })
                ->orWhereExists(function($subQuery) use ($currentMonth, $currentYear) {
                    $subQuery->select(DB::raw(1))
                            ->from('hydrant_checks')
                            ->whereRaw('hydrant_checks.user_id = users.id')
                            ->whereMonth('hydrant_checks.created_at', $currentMonth)
                            ->whereYear('hydrant_checks.created_at', $currentYear);
                });
            })
            ->get()
            ->map(function($user) {
                $aparChecks = $user->apar_checks_count ?? 0;
                $hydrantChecks = $user->hydrant_checks_count ?? 0;
                $totalChecks = $aparChecks + $hydrantChecks;
                
                return [
                    'name' => $user->name,
                    'nip' => $user->nip ?? '-',
                    'apar_checks' => $aparChecks,
                    'hydrant_checks' => $hydrantChecks,
                    'total_checks' => $totalChecks,
                ];
            })
            ->sortByDesc('total_checks')
            ->take(5)
            ->values()
            ->toArray();

        return $topCheckers;

    } catch (\Exception $e) {
        logger()->error('Error getting top checkers: ' . $e->getMessage());
        return [];
    }
}

    /**
     * Get top 5 personil dengan pengecekan terbanyak bulan ini
     * Note: Anda perlu menyesuaikan dengan model dan tabel yang sesuai untuk data pengecekan
     */
    // private function getTopCheckers()
    // {
    //     // Contoh implementasi - sesuaikan dengan struktur tabel pengecekan Anda
    //     // Asumsi: Anda memiliki tabel 'apar_checks' dan 'hydrant_checks' dengan kolom 'user_id' dan 'created_at'
        
    //     $currentMonth = now()->month;
    //     $currentYear = now()->year;

    //     // Jika Anda memiliki model untuk pengecekan, gunakan query seperti ini:
    //     /*
    //     $topCheckers = User::withCount([
    //         'aparChecks as apar_checks_count' => function($query) use ($currentMonth, $currentYear) {
    //             $query->whereMonth('created_at', $currentMonth)
    //                   ->whereYear('created_at', $currentYear);
    //         },
    //         'hydrantChecks as hydrant_checks_count' => function($query) use ($currentMonth, $currentYear) {
    //             $query->whereMonth('created_at', $currentMonth)
    //                   ->whereYear('created_at', $currentYear);
    //         }
    //     ])
    //     ->select('id', 'name', 'nip')
    //     ->havingRaw('(apar_checks_count + hydrant_checks_count) > 0')
    //     ->orderByRaw('(apar_checks_count + hydrant_checks_count) DESC')
    //     ->limit(5)
    //     ->get()
    //     ->map(function($user) {
    //         return [
    //             'name' => $user->name,
    //             'nip' => $user->nip,
    //             'apar_checks' => $user->apar_checks_count,
    //             'hydrant_checks' => $user->hydrant_checks_count,
    //             'total_checks' => $user->apar_checks_count + $user->hydrant_checks_count,
    //         ];
    //     });
    //     */

    //     // Untuk sementara, return array kosong - sesuaikan dengan implementasi aktual Anda
    //     return [];
        
    //     // return $topCheckers;
    // }
}
