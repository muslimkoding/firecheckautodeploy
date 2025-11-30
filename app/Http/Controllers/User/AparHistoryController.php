<?php

namespace App\Http\Controllers\User;

use App\Models\Apar;
use App\Models\Hydrant;
use App\Models\AparCheck;
use App\Models\HydrantCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class AparHistoryController extends Controller
{
    /**
     * Menampilkan form scan QR code
     */
    public function scanForm()
    {
        return view('user.apar.scan');
    }

    /**
     * Handle scan QR code dan tampilkan riwayat
     */
    public function showHistory(Request $request)
    {
        // Validasi input
        $request->validate([
            'qr_code' => 'required|string|max:255'
        ]);

        try {
            // Cari APAR berdasarkan QR code
            $barcode = $request->qr_code;

    $apar = Apar::with(['zone', 'building', 'user', 'floor', 'brand', 'aparType', 'extinguisherCondition'])
            ->where('is_active', true) // Perbaikan: gunakan true, bukan $true
            ->where(function($query) use ($barcode) {
                $query->where('qr_code', $barcode)
                      ->orWhere('number_apar', $barcode);
            })
            ->first();
            if (!$apar) {
                return redirect()->back()->with('error', 'APAR tidak ditemukan!');
            }

            // Ambil 10 riwayat pengecekan terbaru
            $inspections = AparCheck::where('apar_id', $apar->id)
                                        ->with(['user', 'apar', 'zone', 'building', 'group', 'pressure', 'cylinder', 'pinSeal', 'hose', 'handle', 'condition'])
                                        ->orderBy('date_check', 'desc')
                                        ->limit(10)
                                        ->get();

            return view('user.apar.history', compact('apar', 'inspections'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * API endpoint untuk scan (jika butuh API)
     */
    public function apiHistory($qrCode)
    {
        try {
            $apar = Apar::where('qr_code', $qrCode)->first();

            if (!$apar) {
                return response()->json([
                    'success' => false,
                    'message' => 'APAR tidak ditemukan'
                ], 404);
            }

            $inspections = AparInspection::where('apar_id', $apar->id)
                                        ->with(['checker', 'apar'])
                                        ->orderBy('inspection_date', 'desc')
                                        ->limit(10)
                                        ->get()
                                        ->map(function($inspection) {
                                            return [
                                                'inspection_date' => $inspection->inspection_date->format('d-m-Y H:i'),
                                                'checker_name' => $inspection->checker->name ?? 'N/A',
                                                'condition' => $inspection->condition,
                                                'pressure' => $inspection->pressure,
                                                'notes' => $inspection->notes,
                                                'status' => $inspection->status
                                            ];
                                        });

            return response()->json([
                'success' => true,
                'apar' => [
                    'id' => $apar->id,
                    'code' => $apar->code,
                    'location' => $apar->location,
                    'type' => $apar->type,
                    'capacity' => $apar->capacity,
                    'expired_date' => $apar->expired_date?->format('d-m-Y')
                ],
                'inspections' => $inspections
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server'
            ], 500);
        }
    }

    /**
     * Menampilkan 20 last check APAR dan Hydrant tanpa login
     */
    public function getLatestChecks()
    {
        try {
            // Get latest 10 APAR checks tanpa filter user/group
            $latestAparChecks = AparCheck::with([
                    'apar', 
                    'user', 
                    'zone',
                    'pressure',
                    'cylinder', 
                    'pinSeal',
                    'hose',
                    'handle',
                    'condition'
                ])
                ->whereHas('apar', function($query) {
                    $query->where('is_active', true);
                })
                ->latest('date_check')
                ->take(10)
                ->get();

            // Get latest 10 Hydrant checks tanpa filter user/group
            $latestHydrantChecks = HydrantCheck::with([
                    'hydrant',
                    'user', 
                    'zone',
                    'condition'
                ])
                ->whereHas('hydrant', function($query) {
                    $query->where('is_active', true);
                })
                ->latest('date_check')
                ->take(10)
                ->get();

            return view('user.apar.latest', compact('latestAparChecks', 'latestHydrantChecks'));

        } catch (\Exception $e) {
            Log::error('Error getting latest checks: ' . $e->getMessage());
            
            // Return empty collections if error
            $latestAparChecks = collect();
            $latestHydrantChecks = collect();
            
            return view('user.apar.latest', compact('latestAparChecks', 'latestHydrantChecks'))
                ->with('error', 'Terjadi kesalahan saat memuat data');
        }
    }

    /**
     * API endpoint untuk mobile apps
     */
    public function apiLatestChecks()
    {
        try {
            $aparChecks = AparCheck::with(['apar', 'user', 'condition'])
                ->whereHas('apar', function($query) {
                    $query->where('is_active', true);
                })
                ->latest('date_check')
                ->take(10)
                ->get()
                ->map(function($check) {
                    return [
                        'type' => 'APAR',
                        'code' => $check->apar->code ?? 'N/A',
                        'location' => $check->apar->location ?? 'N/A',
                        'check_date' => $check->date_check->format('d-m-Y H:i'),
                        'checker' => $check->user->name ?? 'N/A',
                        'condition' => $check->condition->name ?? 'N/A',
                        'condition_slug' => $check->condition->slug ?? 'N/A',
                        'notes' => $check->notes
                    ];
                });

            $hydrantChecks = HydrantCheck::with(['hydrant', 'user', 'condition'])
                ->whereHas('hydrant', function($query) {
                    $query->where('is_active', true);
                })
                ->latest('date_check')
                ->take(10)
                ->get()
                ->map(function($check) {
                    return [
                        'type' => 'HYDRANT',
                        'code' => $check->hydrant->code ?? 'N/A',
                        'location' => $check->hydrant->location ?? 'N/A',
                        'check_date' => $check->date_check->format('d-m-Y H:i'),
                        'checker' => $check->user->name ?? 'N/A',
                        'condition' => $check->condition->name ?? 'N/A',
                        'condition_slug' => $check->condition->slug ?? 'N/A',
                        'notes' => $check->notes
                    ];
                });

            // Combine and sort by date
            $allChecks = $aparChecks->merge($hydrantChecks)
                ->sortByDesc('check_date')
                ->values()
                ->take(20);

            return response()->json([
                'success' => true,
                'data' => $allChecks,
                'total' => $allChecks->count()
            ]);

        } catch (\Exception $e) {
            Log::error('API Latest Checks Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server',
                'data' => []
            ], 500);
        }
    }

    /**
     * Menampilkan daftar nomor telepon penting
     */
    public function index()
    {
        $emergencyContacts = [
            [
                'name' => 'Pemadam Kebakaran',
                'number' => '113',
                'description' => 'Nomor darurat kebakaran nasional',
                'icon' => 'fa-fire',
                'color' => 'danger',
                'type' => 'emergency'
            ],
            [
                'name' => 'Ambulans',
                'number' => '118',
                'description' => 'Layanan ambulans darurat',
                'icon' => 'fa-ambulance',
                'color' => 'danger',
                'type' => 'emergency'
            ],
            [
                'name' => 'Polisi',
                'number' => '110',
                'description' => 'Nomor darurat kepolisian',
                'icon' => 'fa-shield-alt',
                'color' => 'primary',
                'type' => 'emergency'
            ],
            [
                'name' => 'SAR Nasional',
                'number' => '115',
                'description' => 'Basarnas - Pencarian dan Pertolongan',
                'icon' => 'fa-helicopter',
                'color' => 'warning',
                'type' => 'emergency'
            ],
            [
                'name' => 'ARFF Bandara',
                'number' => '0411-1234567',
                'description' => 'Unit Pemadam Kebakaran Bandara',
                'icon' => 'fa-plane',
                'color' => 'info',
                'type' => 'airport'
            ],
            [
                'name' => 'Pos Security Terminal',
                'number' => '0411-1234568',
                'description' => 'Security utama bandara',
                'icon' => 'fa-user-shield',
                'color' => 'success',
                'type' => 'airport'
            ],
            [
                'name' => 'Medic Bandara',
                'number' => '0411-1234569',
                'description' => 'Unit medis darurat bandara',
                'icon' => 'fa-first-aid',
                'color' => 'danger',
                'type' => 'airport'
            ],
            [
                'name' => 'Operation Control Center',
                'number' => '0411-1234570',
                'description' => 'Pusat kendali operasi bandara',
                'icon' => 'fa-headset',
                'color' => 'secondary',
                'type' => 'airport'
            ],
            [
                'name' => 'Manager ARFF',
                'number' => '0411-1234571',
                'description' => 'Kepala unit pemadam kebakaran',
                'icon' => 'fa-user-tie',
                'color' => 'dark',
                'type' => 'personnel'
            ],
            [
                'name' => 'Supervisor Security',
                'number' => '0411-1234572',
                'description' => 'Supervisor keamanan bandara',
                'icon' => 'fa-user-check',
                'color' => 'dark',
                'type' => 'personnel'
            ],
            [
                'name' => 'Dinas K3 Bandara',
                'number' => '0411-1234573',
                'description' => 'Keselamatan dan Kesehatan Kerja',
                'icon' => 'fa-hard-hat',
                'color' => 'warning',
                'type' => 'airport'
            ],
            [
                'name' => 'PLTD Emergency',
                'number' => '0411-1234574',
                'description' => 'Pembangkit listrik darurat',
                'icon' => 'fa-bolt',
                'color' => 'warning',
                'type' => 'facility'
            ]
        ];

        // Group contacts by type
        $groupedContacts = [
            'emergency' => [
                'title' => 'Darurat Nasional',
                'contacts' => array_filter($emergencyContacts, function($contact) {
                    return $contact['type'] === 'emergency';
                })
            ],
            'airport' => [
                'title' => 'Bandara & Fasilitas',
                'contacts' => array_filter($emergencyContacts, function($contact) {
                    return $contact['type'] === 'airport';
                })
            ],
            'personnel' => [
                'title' => 'Personil Kunci',
                'contacts' => array_filter($emergencyContacts, function($contact) {
                    return $contact['type'] === 'personnel';
                })
            ],
            'facility' => [
                'title' => 'Fasilitas Pendukung',
                'contacts' => array_filter($emergencyContacts, function($contact) {
                    return $contact['type'] === 'facility';
                })
            ]
        ];

        return view('user.apar.phone', compact('groupedContacts', 'emergencyContacts'));
    }

    /**
     * API endpoint untuk mobile apps
     */
    public function apiContacts()
    {
        $contacts = [
            // Same contacts data as above
        ];

        return response()->json([
            'success' => true,
            'data' => $contacts,
            'total' => count($contacts)
        ]);
    }

    /**
     * Menampilkan dashboard utama dengan statistik APAR & Hydrant
     */
    public function indexStats()
    {
        try {
            // Statistik APAR
            $totalApar = Apar::count();
            $activeApar = Apar::where('is_active', true)->count();
            $inactiveApar = Apar::where('is_active', false)->count();
            $aparPercentage = $totalApar > 0 ? round(($activeApar / $totalApar) * 100) : 0;

            // Statistik Hydrant
            $totalHydrant = Hydrant::count();
            $activeHydrant = Hydrant::where('is_active', true)->count();
            $inactiveHydrant = Hydrant::where('is_active', false)->count();
            $hydrantPercentage = $totalHydrant > 0 ? round(($activeHydrant / $totalHydrant) * 100) : 0;

            // Statistik Keseluruhan
            $totalEquipment = $totalApar + $totalHydrant;
            $totalActive = $activeApar + $activeHydrant;
            $totalInactive = $inactiveApar + $inactiveHydrant;
            $overallPercentage = $totalEquipment > 0 ? round(($totalActive / $totalEquipment) * 100) : 0;

            $stats = [
                'apar' => [
                    'total' => $totalApar,
                    'active' => $activeApar,
                    'inactive' => $inactiveApar,
                    'percentage' => $aparPercentage
                ],
                'hydrant' => [
                    'total' => $totalHydrant,
                    'active' => $activeHydrant,
                    'inactive' => $inactiveHydrant,
                    'percentage' => $hydrantPercentage
                ],
                'overall' => [
                    'total' => $totalEquipment,
                    'active' => $totalActive,
                    'inactive' => $totalInactive,
                    'percentage' => $overallPercentage
                ]
            ];

            return view('welcome', compact('stats'));

        } catch (\Exception $e) {
            \Log::error('Dashboard Error: ' . $e->getMessage());
            
            // Return default stats if error
            $stats = [
                'apar' => ['total' => 0, 'active' => 0, 'inactive' => 0, 'percentage' => 0],
                'hydrant' => ['total' => 0, 'active' => 0, 'inactive' => 0, 'percentage' => 0],
                'overall' => ['total' => 0, 'active' => 0, 'inactive' => 0, 'percentage' => 0]
            ];

            return view('welcome', compact('stats'))
                ->with('error', 'Terjadi kesalahan saat memuat data statistik');
        }
    }

    /**
     * API endpoint untuk mobile apps
     */
    public function apiStats()
    {
        try {
            $totalApar = Apar::count();
            $activeApar = Apar::where('is_active', true)->count();
            $totalHydrant = Hydrant::count();
            $activeHydrant = Hydrant::where('is_active', true)->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'apar' => [
                        'total' => $totalApar,
                        'active' => $activeApar,
                        'inactive' => $totalApar - $activeApar
                    ],
                    'hydrant' => [
                        'total' => $totalHydrant,
                        'active' => $activeHydrant,
                        'inactive' => $totalHydrant - $activeHydrant
                    ],
                    'overall' => [
                        'total' => $totalApar + $totalHydrant,
                        'active' => $activeApar + $activeHydrant,
                        'inactive' => ($totalApar - $activeApar) + ($totalHydrant - $activeHydrant)
                    ]
                ],
                'last_updated' => now()->format('Y-m-d H:i:s')
            ]);

        } catch (\Exception $e) {
            \Log::error('API Stats Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server',
                'data' => []
            ], 500);
        }
    }
}
