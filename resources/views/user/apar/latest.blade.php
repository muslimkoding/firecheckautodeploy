<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Last Check APAR & Hydrant</title>
    {{-- @vite(['resources/sass/app.scss', 'resources/js/app.js']) --}}
@vite(['resources/sass/app.scss', 'resources/css/styles.css', 'resources/js/app.js'])

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
            background-attachment: fixed;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-bottom: 80px; /* Space for bottom nav */
        }

        .header-section {
            background: rgb(232, 232, 232);
            border-radius: 20px;
            margin: 14px;
        }

        .check-card {
            border-radius: 15px;
            margin-bottom: 1rem;
            border: 1px solid #e4e4e4;
            background: white;
        }

        .status-badge {
            font-size: 0.75rem;
            padding: 0.4rem 0.8rem;
            border-radius: 15px;
        }

        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
            border-top: none;
        }

        /* Bottom Navigation */
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
            z-index: 1000;
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

        /* Badge for equipment type */
        .equipment-badge {
            font-size: 0.7rem;
            padding: 0.3rem 0.6rem;
            border-radius: 10px;
        }

        .badge-apar {
            background: linear-gradient(135deg, #dc3545, #b02a37);
            color: white;
        }

        .badge-hydrant {
            background: linear-gradient(135deg, #0d6efd, #0a58ca);
            color: white;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .table-responsive {
                font-size: 0.875rem;
            }
            
            .container {
                padding: 0 10px;
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="container mt-3">
        <div class="card check-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <img src="{{ asset('assets/images/logo-injourney-airports.png') }}" class="img-fluid"
                        style="width: 8rem;" alt="">
                    <img src="{{ asset('assets/images/logo-arff-min.png') }}" class="img-fluid" style="width: 2.5rem;"
                        alt="">
                    <img src="{{ asset('assets/images/(UPG)-Sultan-Hasanuddin.png') }}" class="img-fluid"
                        style="width: 8rem;" alt="">
                </div>
            </div>
        </div>

        <!-- Page Title -->
        <div class="card check-card mt-3">
            <div class="card-body text-center">
                <h4 class="fw-bold mb-1">LAST CHECK APAR & HYDRANT</h4>
                <p class="text-muted mb-0">20 Data Pengecekan Terakhir</p>
                <small class="text-muted">
                    Update: {{ now()->format('d-m-Y H:i') }} | 
                    Total: {{ $latestAparChecks->count() + $latestHydrantChecks->count() }} data
                </small>
            </div>
        </div>

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- APAR Checks Section -->
        <div class="card check-card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-fire-extinguisher me-2"></i>LAST CHECK APAR
                    {{-- <span class="badge bg-primary text-white ms-2">{{ $latestAparChecks->count() }} Data</span> --}}
                </h5>
            </div>
            <div class="card-body p-1 bg-light">
                <div class="form-input">
                    @if($latestAparChecks->count() > 0)
                    <div class="table-responsive">
                        <table class="table border mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th width="15%">Tanggal</th>
                                    <th width="10%">Kode</th>
                                    <th width="15%">Lokasi</th>
                                    <th width="10%">Tekanan</th>
                                    <th width="10%">Tabung</th>
                                    <th width="12%">Pemeriksa</th>
                                    <th width="10%">Status</th>
                                    <th width="18%">Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($latestAparChecks as $check)
                                    <tr>
                                        <td>
                                            <small class="text-muted d-block">{{ $check->date_check->format('d-m-Y') }}</small>
                                            <strong>{{ $check->date_check->format('H:i') }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge equipment-badge badge-apar">
                                                {{ $check->apar->number_apar ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td>
                                            <small class="d-inline-block text-truncate" style="max-width: 120px;">
                                                {{ $check->apar->location ?? 'N/A' }}
                                            </small>
                                        </td>
                                        <td>
                                            <span class="fw-semibold">{{ $check->pressure->name ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-semibold">{{ $check->cylinder->name ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <small>{{ $check->user->name ?? 'N/A' }}</small>
                                        </td>
                                        <td>
                                            <span class="badge 
                                                @if($check->condition->slug == 'baik') bg-success
                                                @elseif($check->condition->slug == 'perlu-perbaikan') bg-warning
                                                @elseif($check->condition->slug == 'rusak') bg-danger
                                                @else bg-secondary @endif status-badge">
                                                {{ $check->condition->name ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($check->notes)
                                                <small class="d-inline-block text-truncate" style="max-width: 150px;" 
                                                      data-bs-toggle="tooltip" title="{{ $check->notes }}">
                                                    {{ $check->notes }}
                                                </small>
                                            @else
                                                <small class="text-muted">-</small>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-fire-extinguisher fa-3x text-muted mb-3"></i>
                        <h6 class="text-muted">Belum ada data pengecekan APAR</h6>
                    </div>
                @endif
                </div>
            </div>
        </div>

        <!-- Hydrant Checks Section -->
        <div class="card check-card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-faucet me-2"></i>LAST CHECK HYDRANT
                    {{-- <span class="badge bg-light text-primary ms-2">{{ $latestHydrantChecks->count() }} Data</span> --}}
                </h5>
            </div>
            <div class="card-body p-1 bg-light">
                <div class="form-input">
                    @if($latestHydrantChecks->count() > 0)
                    <div class="table-responsive">
                        <table class="table border align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="15%">Tanggal</th>
                                    <th width="15%">Kode</th>
                                    <th width="20%">Lokasi</th>
                                    <th width="15%">Pemeriksa</th>
                                    <th width="15%">Status</th>
                                    <th width="20%">Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($latestHydrantChecks as $check)
                                    <tr>
                                        <td>
                                            <small class="text-muted d-block">{{ $check->date_check->format('d-m-Y') }}</small>
                                            <strong>{{ $check->date_check->format('H:i') }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge equipment-badge badge-hydrant">
                                                {{ $check->hydrant->number_hydrant ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td>
                                            <small class="d-inline-block text-truncate" style="max-width: 150px;">
                                                {{ $check->hydrant->location ?? 'N/A' }}
                                            </small>
                                        </td>
                                        <td>
                                            <small>{{ $check->user->name ?? 'N/A' }}</small>
                                        </td>
                                        <td>
                                            <span class="badge 
                                                @if($check->condition->slug == 'baik') bg-success
                                                @elseif($check->condition->slug == 'perlu-perbaikan') bg-warning
                                                @elseif($check->condition->slug == 'rusak') bg-danger
                                                @else bg-secondary @endif status-badge">
                                                {{ $check->condition->name ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($check->notes)
                                                <small class="d-inline-block text-truncate" style="max-width: 180px;" 
                                                      data-bs-toggle="tooltip" title="{{ $check->notes }}">
                                                    {{ $check->notes }}
                                                </small>
                                            @else
                                                <small class="text-muted">-</small>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-faucet fa-3x text-muted mb-3"></i>
                        <h6 class="text-muted">Belum ada data pengecekan Hydrant</h6>
                    </div>
                @endif
                </div>
            </div>
        </div>

        <!-- Refresh Button -->
        <div class="text-center mt-4 mb-5">
            <a href="{{ route('public.apar.latest') }}" class="btn btn-primary rounded-4">
                <i class="fas fa-sync-alt me-2"></i>Refresh Data
            </a>
            <a href="{{ route('public.apar.scan') }}" class="btn btn-outline-primary rounded-4 ms-2">
                <i class="fas fa-qrcode me-2"></i>Scan APAR
            </a>
        </div>
    </div>

    <!-- Android-like Bottom Navigation -->
    <nav class="bottom-nav">
        <a href="{{ url('/') }}" class="nav-item">
            <i class="fas fa-home nav-icon"></i>
            <span class="nav-label">Home</span>
        </a>
        <a href="{{ route('public.apar.scan') }}" class="nav-item">
            <i class="fas fa-qrcode nav-icon"></i>
            <span class="nav-label">Apar</span>
        </a>
        <a href="{{ route('public.apar.latest') }}" class="nav-item active">
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

    <script>
        // Simple active state management for bottom nav
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Auto refresh every 5 minutes
            setInterval(function() {
                window.location.reload();
            }, 300000); // 5 minutes
        });
    </script>
</body>
</html>