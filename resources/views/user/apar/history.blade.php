<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pengecekan APAR</title>
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --light-bg: #f8f9fa;
        }
        
        body {
            background: #EBF7F9;
            background-image: url("{{ asset('assets/images/supgraf-injourney-13-min.png') }}");
            background-position: bottom right;
            background-repeat: no-repeat;
            background-size: 600px auto;
            /* width 300px, height auto */
            background-attachment: fixed;
            /* min-height: 100vh; */

            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;

        }

        /* menu android */
        .landing-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Header Styles */
        .app-header {
            text-align: center;
            padding: 2rem 1rem;
            color: white;
        }

        .app-logo {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            backdrop-filter: blur(10px);
        }

        .app-logo i {
            font-size: 2.5rem;
            color: white;
        }

        .app-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .app-subtitle {
            font-size: 1rem;
            opacity: 0.9;
            margin-bottom: 0;
        }

        /* Card Grid Styles */
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            padding: 0 1rem;
            margin-top: 2rem;
        }

        .menu-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            text-decoration: none;
            color: #333;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 200px;
        }

        .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
            text-decoration: none;
            color: #333;
        }

        .card-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            font-size: 1.8rem;
        }

        .card-icon.login {
            background: linear-gradient(135deg, var(--primary-color), #0a58ca);
        }

        .card-icon.scan {
            background: linear-gradient(135deg, var(--success-color), #157347);
        }

        .card-icon.history {
            background: linear-gradient(135deg, var(--warning-color), #ffcd39);
        }

        .card-icon.report {
            background: linear-gradient(135deg, var(--danger-color), #b02a37);
        }

        .card-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .card-description {
            font-size: 0.9rem;
            color: #666;
            line-height: 1.4;
        }

        /* Android-like Bottom Navigation */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            padding: 0.5rem 1rem;
            display: flex;
            justify-content: space-around;
            align-items: center;
            box-shadow: 0 -2px 20px rgba(0, 0, 0, 0.1);
        }

        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0.5rem;
            text-decoration: none;
            color: #666;
            transition: all 0.3s ease;
            border-radius: 12px;
            min-width: 60px;
        }

        .nav-item.active {
            color: var(--primary-color);
        }

        .nav-item:hover {
            color: var(--primary-color);
            background: rgba(13, 110, 253, 0.1);
        }

        .nav-icon {
            font-size: 1.4rem;
            margin-bottom: 0.25rem;
        }

        .nav-label {
            font-size: 0.7rem;
            font-weight: 500;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .app-title {
                font-size: 1.8rem;
            }
            
            .menu-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .menu-card {
                padding: 1.5rem;
                min-height: 180px;
            }
            
            .card-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .app-header {
                padding: 1.5rem 1rem;
            }
            
            .app-title {
                font-size: 1.5rem;
            }
            
            .bottom-nav {
                padding: 0.5rem;
            }
            
            .nav-item {
                min-width: 50px;
                padding: 0.4rem;
            }
            
            .nav-icon {
                font-size: 1.2rem;
            }
            
            .nav-label {
                font-size: 0.65rem;
            }
        }

        /* Additional Features */
        .feature-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--danger-color);
            color: white;
            border-radius: 10px;
            padding: 4px 8px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .card-wrapper {
            position: relative;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            padding: 0 1rem;
            margin-top: 2rem;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            padding: 1rem;
            text-align: center;
            backdrop-filter: blur(10px);
            border: 1px solid #dadada;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .stat-number {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.8rem;
            color: #666;
        }
        /* end menu android */

        .header-section {
            /* background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); */
            background: rgb(232, 232, 232);
            /* color: white; */
            border-radius: 20px 20px 20px 20px;
            margin-bottom: 2rem;
            margin: 14px;
        }

        .apar-card {
            border-radius: 15px;
            /* box-shadow: 0 5px 15px rgba(0,0,0,0.1); */
            margin-bottom: 2rem;
            border: 1px solid #e4e4e4;
        }

        .status-badge {
            font-size: 0.8rem;
            padding: 0.5rem 1rem;
            border-radius: 20px;
        }

        .inspection-item {
            border-left: 4px solid #007bff;
            transition: all 0.3s;
        }

        .inspection-item:hover {
            background-color: #f8f9fa;
            transform: translateX(5px);
        }
    </style>
</head>

<body>
    <!-- Header -->
    {{-- <div class="header-section py-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col">
                    <a href="{{ route('public.apar.scan') }}" class="btn btn-light btn-sm mb-3">
                        <i class="fas fa-arrow-left me-2"></i>Scan Lagi
                    </a>
                    <h2 class="fw-bold mb-0">RIWAYAT CHECKLIST APAR</h2>
                    <p class="mb-0">10 Checklist Terakhir</p>
                </div>
            </div>
        </div>
    </div> --}}

    <div class="container">

        <div class="card mt-4 mb-4 rounded-4 apar-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    {{-- <a href="{{ route('public.apar.scan') }}" class="text-decoration-none text-secondary"><i class="fas fa-arrow-left"></i> Kembali</a> --}}
                    {{-- <div class="">
                        <h5 class="fw-bold mb-0">RIWAYAT <br> CHECKLIST APAR</h5>
                    <p class="mb-0">10 Checklist Terakhir</p>
                    </div> --}}
                    <img src="{{ asset('assets/images/logo-injourney-airports.png') }}" class="img-fluid"
                        style="width: 8rem;" alt="">
                    <img src="{{ asset('assets/images/logo-arff-min.png') }}" class="img-fluid" style="width: 2.5rem;"
                        alt="">
                    <img src="{{ asset('assets/images/(UPG)-Sultan-Hasanuddin.png') }}" class="img-fluid"
                        style="width: 8rem;" alt="">

                </div>
            </div>
        </div>

        <!-- APAR Information Card -->
        <div class="row">
            <div class="col-md-6 ">
                <div class="card apar-card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-fire-extinguisher me-2"></i>Informasi APAR
                        </h5>
                    </div>
                    <div class="card-body p-1 bg-light">
                        <div class="form-input">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Kode APAR</th>
                                    <td>: <strong>{{ $apar->number_apar ?? 'N/A' }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Lokasi</th>
                                    <td>: {{ $apar->location ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Tipe</th>
                                    <td>: {{ $apar->aparType->name ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 ">
                <div class="card apar-card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-fire-extinguisher me-2"></i>Informasi APAR
                        </h5>
                    </div>
                    <div class="card-body p-1 bg-light">
                        <div class="form-input">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Kapasitas</th>
                                    <td>: {{ $apar->formatted_weight ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Kadaluarsa</th>
                                    <td>: {{ $apar->expired_date ? $apar->expired_date->format('d-m-Y') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>:
                                        @if ($apar->expired_date && $apar->expired_date->isPast())
                                            <span class="badge bg-danger status-badge">KADALUARSA</span>
                                        @else
                                            <span class="badge bg-success status-badge">AKTIF</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <!-- Inspection History -->
        <div class="card apar-card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-clipboard-list me-2"></i>Riwayat Pemeriksaan <small class="text-muted">(10
                        Checklist Terakhir)</small>
                </h5>
            </div>
            {{-- <div class="card-body">
                @if ($inspections->count() > 0)
                    @foreach ($inspections as $inspection)
                        <div class="inspection-item p-3 mb-3 bg-white rounded shadow-sm">
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <strong>{{ $inspection->date_check->format('d-m-Y H:i') }}</strong>
                                </div>
                                <div class="col-md-2">
                                    <span class="badge 
                                        @if ($inspection->condition == 'baik') bg-success
                                        @elseif($inspection->condition == 'perbaikan') bg-warning
                                        @else bg-danger
                                        @endif status-badge">
                                        {{ strtoupper($inspection->condition) }}
                                    </span>
                                </div>
                                <div class="col-md-2">
                                    <small class="text-muted">Tekanan:</small>
                                    <br>
                                    <strong>{{ $inspection->pressure ?? 'N/A' }}</strong>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted">Pemeriksa:</small>
                                    <br>
                                    <strong>{{ $inspection->user->name ?? 'N/A' }}</strong>
                                </div>
                                <div class="col-md-2">
                                    <small class="text-muted">Status:</small>
                                    <br>
                                    <span class="badge 
                                        @if ($inspection->status == 'approved') bg-success
                                        @elseif($inspection->status == 'pending') bg-warning
                                        @else bg-secondary
                                        @endif">
                                        {{ $inspection->condition }}
                                    </span>
                                </div>
                            </div>
                            @if ($inspection->notes)
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <small class="text-muted">Catatan:</small>
                                        <p class="mb-0">{{ $inspection->notes }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-clipboard-check fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum ada riwayat pengecekan</h5>
                        <p class="text-muted">APAR ini belum pernah dilakukan pengecekan</p>
                    </div>
                @endif
            </div> --}}
            {{-- <div class="card-body">
                @if ($inspections->count() > 0)
                    @foreach ($inspections as $inspection)
                        <div class="inspection-item p-3 mb-3 bg-white rounded shadow-sm">
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <strong>{{ $inspection->date_check->format('d-m-Y H:i') }}</strong>
                                </div>
                                <div class="col-md-2">
                                    <small class="text-muted">Tekanan:</small>
                                    <br>
                                    <strong>{{ $inspection->pressure->name ?? 'N/A' }}</strong>
                                </div>
                                <div class="col-md-3">
                                    <small class="text-muted">Pemeriksa:</small>
                                    <br>
                                    <strong>{{ $inspection->user->name ?? 'N/A' }}</strong>
                                </div>
                                <div class="col-md-2">
                                    <small class="text-muted">Status:</small>
                                    <br>
                                    <span class="badge 
                                        @if ($inspection->condition->slug == 'baik') bg-success
                                        @elseif($inspection->condition->slug == 'perlu-perbaikan') bg-warning
                                        @elseif($inspection->condition->slug == 'rusak') bg-danger
                                        @else bg-secondary
                                        @endif">
                                        {{ $inspection->condition->name ?? 'N/A' }}
                                    </span>
                                </div>
                            </div>
                            @if ($inspection->notes)
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <small class="text-muted">Catatan:</small>
                                        <p class="mb-0">{{ $inspection->notes }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-clipboard-check fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum ada riwayat pengecekan</h5>
                        <p class="text-muted">APAR ini belum pernah dilakukan pengecekan</p>
                    </div>
                @endif
            </div> --}}
            <div class="card-body p-1 bg-light">
                <div class="form-input">
                    @if ($inspections->count() > 0)
                        <div class="table-responsive">
                            <table class="table border mb-0 align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Tekanan</th>
                                        <th>Tabung</th>
                                        <th>Pin & Seal</th>
                                        <th>Hose</th>
                                        <th>Handle</th>
                                        <th>Pemeriksa</th>
                                        <th>Status</th>
                                        <th>Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($inspections as $inspection)
                                        <tr>
                                            <td>
                                                {{-- <strong>{{ $inspection->date_check->format('d-m-Y H:i') }}</strong> --}}
                                                <strong>{{ $inspection->updated_at->format('d-m-Y H:i') }}</strong>
                                            </td>
                                            <td>
                                                <span
                                                    class="fw-semibold">{{ $inspection->pressure->name ?? 'N/A' }}</span>
                                            </td>
                                            <td>
                                                <span
                                                    class="fw-semibold">{{ $inspection->cylinder->name ?? 'N/A' }}</span>
                                            </td>
                                            <td>
                                                <span
                                                    class="fw-semibold">{{ $inspection->pinSeal->name ?? 'N/A' }}</span>
                                            </td>
                                            <td>
                                                <span class="fw-semibold">{{ $inspection->hose->name ?? 'N/A' }}</span>
                                            </td>
                                            <td>
                                                <span
                                                    class="fw-semibold">{{ $inspection->handle->name ?? 'N/A' }}</span>
                                            </td>
                                            <td>
                                                {{ $inspection->user->name ?? 'N/A' }}
                                            </td>
                                            <td>
                                                <span
                                                    class="badge 
                                            @if ($inspection->condition->slug == 'baik') bg-success
                                            @elseif($inspection->condition->slug == 'perlu-perbaikan') bg-warning
                                            @elseif($inspection->condition->slug == 'rusak') bg-danger
                                            @else bg-secondary @endif">
                                                    {{ $inspection->condition->name ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($inspection->notes)
                                                    <span class="d-inline-block text-truncate" style="max-width: 200px;"
                                                        data-bs-toggle="tooltip" title="{{ $inspection->notes }}">
                                                        {{ $inspection->notes }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-clipboard-check fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada riwayat pengecekan</h5>
                            <p class="text-muted">APAR ini belum pernah dilakukan pengecekan</p>
                        </div>
                    @endif
                </div>
            </div>
            {{-- <div class="card-body">
                @if ($inspections->count() > 0)
                    <div class="row">
                        @foreach ($inspections as $inspection)
                        <div class="col-md-6 mb-3">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="card-title text-primary">
                                            {{ $inspection->date_check->format('d M Y H:i') }}
                                        </h6>
                                        <span class="badge 
                                            @if ($inspection->condition->slug == 'baik') bg-success
                                            @elseif($inspection->condition->slug == 'perlu-perbaikan') bg-warning
                                            @elseif($inspection->condition->slug == 'rusak') bg-danger
                                            @else bg-secondary
                                            @endif">
                                            {{ $inspection->condition->name ?? 'N/A' }}
                                        </span>
                                    </div>
                                    
                                    <div class="row small text-muted">
                                        <div class="col-6">
                                            <strong>Tekanan:</strong><br>
                                            {{ $inspection->pressure->name ?? 'N/A' }}
                                        </div>
                                        <div class="col-6">
                                            <strong>Pemeriksa:</strong><br>
                                            {{ $inspection->user->name ?? 'N/A' }}
                                        </div>
                                    </div>
            
                                    @if ($inspection->notes)
                                    <div class="mt-3 pt-2 border-top">
                                        <small class="text-muted">Catatan:</small>
                                        <p class="mb-0 small">{{ $inspection->notes }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-clipboard-check fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum ada riwayat pengecekan</h5>
                        <p class="text-muted">APAR ini belum pernah dilakukan pengecekan</p>
                    </div>
                @endif
            </div> --}}
            {{-- <div class="card-body">
                @if ($inspections->count() > 0)
                    <div class="timeline">
                        @foreach ($inspections as $inspection)
                        <div class="timeline-item mb-4">
                            <div class="timeline-marker 
                                @if ($inspection->condition->slug == 'baik') bg-success
                                @elseif($inspection->condition->slug == 'perlu-perbaikan') bg-warning
                                @elseif($inspection->condition->slug == 'rusak') bg-danger
                                @else bg-secondary
                                @endif">
                            </div>
                            <div class="timeline-content">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">{{ $inspection->date_check->format('d M Y H:i') }}</h6>
                                    <span class="badge 
                                        @if ($inspection->condition->slug == 'baik') bg-success
                                        @elseif($inspection->condition->slug == 'perlu-perbaikan') bg-warning
                                        @elseif($inspection->condition->slug == 'rusak') bg-danger
                                        @else bg-secondary
                                        @endif">
                                        {{ $inspection->condition->name ?? 'N/A' }}
                                    </span>
                                </div>
                                <div class="row small">
                                    <div class="col-md-6">
                                        <strong>Tekanan:</strong> {{ $inspection->pressure->name ?? 'N/A' }}
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Oleh:</strong> {{ $inspection->user->name ?? 'N/A' }}
                                    </div>
                                </div>
                                @if ($inspection->notes)
                                <div class="mt-2">
                                    <small class="text-muted">Catatan:</small>
                                    <p class="mb-0 small">{{ $inspection->notes }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-clipboard-check fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Belum ada riwayat pengecekan</h5>
                        <p class="text-muted">APAR ini belum pernah dilakukan pengecekan</p>
                    </div>
                @endif
            </div> --}}

            <style>
                .timeline {
                    position: relative;
                    padding-left: 30px;
                }

                .timeline-item {
                    position: relative;
                }

                .timeline-marker {
                    position: absolute;
                    left: -30px;
                    top: 5px;
                    width: 12px;
                    height: 12px;
                    border-radius: 50%;
                    background: #6c757d;
                }

                .timeline-content {
                    background: #f8f9fa;
                    padding: 15px;
                    border-radius: 8px;
                    border-left: 3px solid #dee2e6;
                }
            </style>
        </div>

        <!-- Scan Again Button -->
        <div class="text-center mt-4 mb-4">
            <a href="{{ route('public.apar.scan') }}" class="btn btn-light border rounded-4">
                <i class="fas fa-qrcode me-2"></i>Scan APAR Lainnya
            </a>
        </div>

    </div>

    {{-- <footer class="footer mt-auto py-3 bg-body-tertiary sticky-bottom"> <div class="container"> <span class="text-body-secondary">Place sticky footer content here.</span> </div> </footer> --}}


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    {{-- <script src="https://kit.fontawesome.com/your-fontawesome-kit.js"></script> --}}

    <!-- Android-like Bottom Navigation -->
    <nav class="bottom-nav">
        <a href="{{ url('/') }}" class="nav-item ">
            <i class="fas fa-home nav-icon"></i>
            <span class="nav-label">Home</span>
        </a>
        <a href="{{ route('public.apar.scan') }}" class="nav-item active">
            <i class="fas fa-qrcode nav-icon"></i>
            <span class="nav-label">Apar</span>
        </a>
        <a href="{{ route('public.apar.latest') }}" class="nav-item">
            <i class="fas fa-history nav-icon"></i>
            <span class="nav-label">Riwayat</span>
        </a>
        <a href="{{ route('public.hydrant.scan') }}" class="nav-item">
            <i class="fas fa-qrcode nav-icon"></i>
            <span class="nav-label">Hydrant</span>
        </a>
        <a href="{{ route('public.apar.contacts') }}" class="nav-item">
            <i class="fa-solid fa-phone nav-icon"></i>
            <span class="nav-label">Emergency</span>
        </a>
    </nav>

    <!-- Font Awesome for Icons -->
<script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

    <script>
        // Simple active state management for bottom nav
        document.addEventListener('DOMContentLoaded', function() {
            const navItems = document.querySelectorAll('.nav-item');
            
            navItems.forEach(item => {
                item.addEventListener('click', function() {
                    navItems.forEach(nav => nav.classList.remove('active'));
                    this.classList.add('active');
                });
            });

            // Add subtle animation to cards on load
            const cards = document.querySelectorAll('.menu-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
</body>

</html>
