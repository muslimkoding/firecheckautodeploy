@extends('admin.template')

@section('title', 'Halaman Dasboard')

@section('breadcrumb')
    <h1 class="mt-4">Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item ">Admin</li>
    </ol>
@endsection

@section('content')

    <!-- Summary Cards -->
    @if (session('info'))
        <div class="alert alert-info">
            {{ session('info') }}
        </div>
    @endif

    @if ($assignedZones->count() > 0)
        <div class="alert alert-success">
            <h6><i class="fas fa-map-marker-alt"></i> Zona yang Ditugaskan:</h6>
            @foreach ($assignedZones as $zone)
                <span class="badge bg-primary me-1">{{ $zone->name }}</span>
            @endforeach
        </div>
    @endif

    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-info-circle me-2 text-primary"></i>
                    Total APAR + Hydrant
                </div>
                <div class="card-body p-1 bg-light text-center">
                    <div class="form-input">
                        <h4>{{ $summary['totalCombined'] }}</h4>
                        <p>Total APAR + Hydrant<br><small>Bulan Ini</small></p>
                        <small class="text-muted">APAR: {{ $summary['totalApars'] }} | Hydrant:
                            {{ $summary['totalHydrants'] }}</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mt-4 mt-md-0">
                <div class="card-header">
                    <i class="fas fa-info-circle me-2 text-success"></i> Sudah Dicek
                </div>
                <div class="card-body text-center p-1 bg-light">
                    <div class="form-input">
                        <h4>{{ $summary['checkedCombinedCount'] }}</h4>
                        <p>Sudah Dicek<br><small>Bulan Ini</small></p>
                        <small class="text-muted">APAR: {{ $summary['checkedAparCount'] }} | Hydrant:
                            {{ $summary['checkedHydrantCount'] }}</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mt-4 mt-md-0">
                <div class="card-header">
                    <i class="fas fa-info-circle me-2 text-warning"></i> Belum Dicek
                </div>
                <div class="card-body text-center p-1 bg-light">
                    <div class="form-input">
                        <h4>{{ $summary['uncheckedCombinedCount'] }}</h4>
                        <p>Belum Dicek<br><small>Bulan Ini</small></p>
                        <small class="text-muted">APAR: {{ $summary['uncheckedAparCount'] }} | Hydrant:
                            {{ $summary['uncheckedHydrantCount'] }}</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mt-4 mt-md-0">
                <div class="card-header">
                    <i class="fas fa-info-circle me-2 text-info"></i> Progress
                </div>
                <div class="card-body text-center p-1 bg-light">
                    <div class="form-input">
                        <h4>{{ $summary['progress'] }}%</h4>
                        <p>Progress APAR + Hydrant<br><small>Bulan Ini</small></p>
                        <small class="text-muted">APAR: {{ $summary['aparProgress'] }}% | Hydrant:
                            {{ $summary['hydrantProgress'] }}%</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="progress mt-3 mb-3" style="height: 25px;">
        <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar"
            style="width: {{ $summary['progress'] }}%" aria-valuenow="{{ $summary['progress'] }}" aria-valuemin="0"
            aria-valuemax="100">
            {{ $summary['progress'] }}% Complete
        </div>
    </div>

    <!-- Group Monitoring Section (Hanya untuk Admin/Superadmin) -->
    {{-- @if (($summary['userType'] ?? 'user') === 'admin') --}}
    {{-- @if (auth()->user()->hasRole('superadmin')) --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header ">
                    <i class="fas fa-users me-2"></i>
                    Monitoring Semua Regu Aktif
                    <small class="float-end">Total {{ $monitoringData['groups']->count() }} Regu Aktif</small>
                </div>
                <div class="card-body p-1 bg-light">
                    <div class="table-responsive">
                        <div class="form-input">
                            <table class="table border table-striped mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama Regu</th>
                                        <th class="text-center">Anggota</th>
                                        <th class="text-center">Zona</th>
                                        <th class="text-center">Total APAR</th>
                                        <th class="text-center">Total Hydrant</th>
                                        <th class="text-center">Sudah Dicek</th>
                                        <th class="text-center">Progress</th>
                                        {{-- <th class="text-center">Detail</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($monitoringData['groups'] as $group)
                                        <tr>
                                            <td>
                                                <strong>{{ $group['name'] }}</strong>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-info">{{ $group['user_count'] }} Orang</span>
                                            </td>
                                            <td class="text-center">
                                                @foreach ($group['zones'] as $zone)
                                                    <span class="badge bg-secondary mb-1">{{ $zone->name }}</span><br>
                                                @endforeach
                                            </td>
                                            <td class="text-center">
                                                <strong>{{ $group['total_apar'] }}</strong>
                                                <br>
                                                <small class="text-muted">
                                                    {{ $group['checked_apar'] }}/{{ $group['total_apar'] }}
                                                </small>
                                            </td>
                                            <td class="text-center">
                                                <strong>{{ $group['total_hydrant'] }}</strong>
                                                <br>
                                                <small class="text-muted">
                                                    {{ $group['checked_hydrant'] }}/{{ $group['total_hydrant'] }}
                                                </small>
                                            </td>
                                            <td class="text-center">
                                                <strong class="text-success">{{ $group['checked_combined'] }}</strong>
                                                <br>
                                                <small class="text-muted">
                                                    APAR: {{ $group['checked_apar'] }} |
                                                    Hydrant: {{ $group['checked_hydrant'] }}
                                                </small>
                                            </td>
                                            <td class="text-center">
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar progress-bar-striped bg-success" role="progressbar"
                                                        style="width: {{ $group['progress'] }}%"
                                                        aria-valuenow="{{ $group['progress'] }}" aria-valuemin="0"
                                                        aria-valuemax="100">
                                                        {{ $group['progress'] }}%
                                                    </div>
                                                </div>
                                                <small class="text-muted">
                                                    APAR: {{ $group['apar_progress'] }}% |
                                                    Hydrant: {{ $group['hydrant_progress'] }}%
                                                </small>
                                            </td>
                                            {{-- <td class="text-center">
                                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                                    data-bs-target="#groupDetailModal" data-group-id="{{ $group['id'] }}"
                                                    data-group-name="{{ $group['name'] }}">
                                                    <i class="fas fa-eye"></i> Lihat
                                                </button>
                                            </td> --}}
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4">
                                                <i class="fas fa-users fa-2x text-muted mb-2"></i>
                                                <p class="text-muted">Tidak ada regu aktif</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Zona Assignment Overview -->
    <div class="row mt-4 mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-map-marked-alt me-2"></i>
                    Overview Penugasan Zona
                </div>
                <div class="card-body p-1 bg-light">
                    <div class="form-input">
                        <div class="row">
                            @foreach ($monitoringData['zones'] as $zone)
                                <div class="col-md-4 mb-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <h6 class="card-title">{{ $zone->name }}</h6>
                                            <p class="card-text">
                                                <small class="text-muted">
                                                    @if ($zone->groups->count() > 0)
                                                        Ditugaskan ke:
                                                        @foreach ($zone->groups as $group)
                                                            <span class="badge bg-primary">{{ $group->name }}</span>
                                                        @endforeach
                                                    @else
                                                        <span class="text-warning">Belum ditugaskan</span>
                                                    @endif
                                                </small>
                                            </p>
                                            <div class="d-flex justify-content-between">
                                                <small>
                                                    APAR:
                                                    <strong>{{ $zone->apars->where('is_active', true)->count() }}</strong>
                                                </small>
                                                <small>
                                                    Hydrant:
                                                    <strong>{{ $zone->hydrants->where('is_active', true)->count() }}</strong>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- @endif --}}

    <div class="row">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-area me-1"></i>
                    Grafik Pengecekan APAR & Hydrant (6 Bulan Terakhir)
                </div>
                <div class="p-1 bg-light">
                    <div class="form-input">
                        <div class="card-body p-1 bg-light rounded-3">
                            <canvas id="areaChart" style="max-height: 400px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-bar me-1"></i>
                    Grafik Perbandingan APAR & Hydrant (6 Bulan Terakhir)
                </div>
                <div class="p-1 bg-light">
                    <div class="form-input">
                        <div class="card-body p-1 bg-light rounded-3">
                            <canvas id="barChart" style="max-height: 400px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Detail Tables -->
    <div class="row mb-4">
        <div class="col-xl-6">
            <div class="card mb-3">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    Detail Data APAR
                </div>
                <div class="card-body p-1 bg-light">
                    <div class="table-responsive">
                        <div class="form-input">
                            <table class="table border table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>Keterangan</th>
                                        <th class="text-center">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Total APAR</td>
                                        <td class="text-center"><strong>{{ $summary['totalApars'] }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Sudah Dicek</td>
                                        <td class="text-center"><strong
                                                class="text-success">{{ $summary['checkedAparCount'] }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Belum Dicek</td>
                                        <td class="text-center"><strong
                                                class="text-warning">{{ $summary['uncheckedAparCount'] }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Progress</td>
                                        <td class="text-center"><strong>{{ $summary['aparProgress'] }}%</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card mb-3">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    Detail Data Hydrant
                </div>
                <div class="card-body p-1 bg-light">
                    <div class="table-responsive">
                        <div class="form-input">
                            <table class="table border table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>Keterangan</th>
                                        <th class="text-center">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Total Hydrant</td>
                                        <td class="text-center"><strong>{{ $summary['totalHydrants'] }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Sudah Dicek</td>
                                        <td class="text-center"><strong
                                                class="text-success">{{ $summary['checkedHydrantCount'] }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Belum Dicek</td>
                                        <td class="text-center"><strong
                                                class="text-warning">{{ $summary['uncheckedHydrantCount'] }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>Progress</td>
                                        <td class="text-center"><strong>{{ $summary['hydrantProgress'] }}%</strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Latest Checks Table -->
    <div class="card rounded-3 mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Data Pengecekan Terakhir (5 APAR & 5 Hydrant Terakhir)
        </div>
        <div class="card-body p-1 bg-light">
            <div class="table-responsive">
                <div class="form-input">
                    <table id="datatablesSimple" class="table border bg-white">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Jenis</th>
                                <th>Nomor</th>
                                <th>Lokasi</th>
                                <th>Zona</th>
                                <th>Pemeriksa</th>
                                <th>Tanggal Cek</th>
                                <th>Kondisi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                            @endphp
                            @foreach ($latestChecks['latestAparChecks'] as $check)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td><span class="badge bg-primary">APAR</span></td>
                                    <td>{{ $check->apar->number_apar ?? '-' }}</td>
                                    <td>{{ $check->apar->location ?? '-' }}</td>
                                    <td>{{ $check->zone->name ?? '-' }}</td>
                                    <td>{{ $check->user->name ?? '-' }}</td>
                                    <td>{{ $check->formatted_check_date }}</td>
                                    {{-- <td>{!! $check->apar->extinguisher_condition_badge ?? '-' !!}</td> --}}
                                    {{-- <td>{!! $check->condition->name ?? '-' !!}</td> --}}
                                    <td>{!! $check->status_badge ?? '-' !!}</td>
                                </tr>
                            @endforeach
                            @foreach ($latestChecks['latestHydrantChecks'] as $check)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td><span class="badge bg-info">Hydrant</span></td>
                                    <td>{{ $check->hydrant->number_hydrant ?? '-' }}</td>
                                    <td>{{ $check->hydrant->location ?? '-' }}</td>
                                    <td>{{ $check->zone->name ?? '-' }}</td>
                                    <td>{{ $check->user->name ?? '-' }}</td>
                                    <td>{{ $check->formatted_check_date }}</td>
                                    {{-- <td>{!! $check->hydrant->extinguisher_condition_badge ?? '-' !!}</td> --}}
                                    <td>{!! $check->status_badge ?? '-' !!}</td>
                                </tr>
                            @endforeach
                            @if ($latestChecks['latestAparChecks']->isEmpty() && $latestChecks['latestHydrantChecks']->isEmpty())
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada data pengecekan</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('sweetalert2::index')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script>
        // Chart Data
        const chartData = @json($chartData);

        // Area Chart
        const areaCtx = document.getElementById('areaChart').getContext('2d');
        const areaChart = new Chart(areaCtx, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                        label: 'APAR',
                        data: chartData.aparData,
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Hydrant',
                        data: chartData.hydrantData,
                        borderColor: 'rgb(255, 99, 132)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Trend Pengecekan 6 Bulan Terakhir'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Bar Chart
        const barCtx = document.getElementById('barChart').getContext('2d');
        const barChart = new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: chartData.labels,
                datasets: [{
                        label: 'APAR',
                        data: chartData.aparData,
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgb(75, 192, 192)',
                        borderWidth: 1
                    },
                    {
                        label: 'Hydrant',
                        data: chartData.hydrantData,
                        backgroundColor: 'rgba(255, 99, 132, 0.6)',
                        borderColor: 'rgb(255, 99, 132)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Perbandingan Pengecekan 6 Bulan Terakhir'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
@endpush
