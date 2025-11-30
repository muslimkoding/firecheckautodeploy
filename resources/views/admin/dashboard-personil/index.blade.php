@extends('admin.template')

@section('title', 'Dashboard Personil')

@section('breadcrumb')
    <h1 class="mt-4">Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item">Personil</li>
    </ol>
@endsection

@section('content')
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- A. Summary Cards -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-users me-2 text-primary"></i>
                    Total Personil
                </div>
                <div class="card-body p-1 bg-light text-center">
                    <div class="form-input">
                        <h4>{{ $summary['totalUsers'] }}</h4>
                        <p>Total Karyawan<br><small>Semua Jabatan</small></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mt-4 mt-md-0">
                <div class="card-header">
                    <i class="fas fa-user-tie me-2 text-success"></i> Operation Chief
                </div>
                <div class="card-body text-center p-1 bg-light">
                    <div class="form-input">
                        <h4>{{ $summary['operationChief'] }}</h4>
                        <p>Total Operation Chief<br><small>Jabatan</small></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mt-4 mt-md-0">
                <div class="card-header">
                    <i class="fas fa-user-shield me-2 text-warning"></i> Chief Assistant
                </div>
                <div class="card-body text-center p-1 bg-light">
                    <div class="form-input">
                        <h4>{{ $summary['chiefAssistant'] }}</h4>
                        <p>Total Chief Assistant<br><small>Jabatan</small></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card mt-4 mt-md-0">
                <div class="card-header">
                    <i class="fas fa-user-check me-2 text-info"></i> Officer
                </div>
                <div class="card-body text-center p-1 bg-light">
                    <div class="form-input">
                        <h4>{{ $summary['officer'] }}</h4>
                        <p>Total Officer<br><small>Jabatan</small></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- B. Chart Komposisi Kompetensi -->
    <div class="row mt-4">
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-pie me-1"></i>
                    Komposisi Kompetensi Personil
                </div>
                <div class="p-1 bg-light">
                    <div class="form-input">
                        <div class="card-body p-1">
                            <canvas id="competencyChart" style="max-height: 400px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- H. Top 5 Pengecekan Terbanyak -->
        <div class="col-xl-6">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-trophy me-1"></i>
                    Top 5 Personil Pengecekan Terbanyak (Bulan Ini)
                </div>
                <div class="card-body p-1 bg-light">
                    <div class="table-responsive">
                        <div class="form-input">
                            <table class="table border table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Nama</th>
                                        <th class="text-center">APAR</th>
                                        <th class="text-center">Hydrant</th>
                                        <th class="text-center">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($topCheckers as $index => $checker)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <strong>{{ $checker['name'] }}</strong>
                                                @if($checker['nip'])
                                                    <br><small class="text-muted">{{ $checker['nip'] }}</small>
                                                @endif
                                            </td>
                                            <td class="text-center">{{ $checker['apar_checks'] }}</td>
                                            <td class="text-center">{{ $checker['hydrant_checks'] }}</td>
                                            <td class="text-center"><strong class="text-primary">{{ $checker['total_checks'] }}</strong></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Tidak ada data pengecekan</td>
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

    <!-- C. Table Department Head -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Airport Rescue & Fire Fighting Operation Department Head
        </div>
        <div class="card-body p-1 bg-light">
            <div class="table-responsive">
                <div class="form-input">
                    <table class="table border table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>Nama</th>
                                <th>NIP</th>
                                <th>Jabatan</th>
                                <th>Tipe Karyawan</th>
                                <th>Kompetensi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($departmentHeads as $person)
                                <tr>
                                    <td>{{ $person['name'] }}</td>
                                    <td>{{ $person['nip'] ?? '-' }}</td>
                                    <td>{{ $person['position'] }}</td>
                                    <td>{{ $person['employee_type'] }}</td>
                                    <td>{{ $person['competency'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada data Department Head</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- D. Table Operation Chief dengan Group -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Airport Rescue & Fire Fighting Operation Chief
        </div>
        <div class="card-body p-1 bg-light">
            <div class="table-responsive">
                <div class="form-input">
                    <table class="table border table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>Nama</th>
                                <th>NIP</th>
                                <th>Jabatan</th>
                                <th>Group</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($operationChiefs as $person)
                                <tr>
                                    <td>{{ $person['name'] }}</td>
                                    <td>{{ $person['nip'] ?? '-' }}</td>
                                    <td>{{ $person['position'] }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $person['group'] }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Tidak ada data Operation Chief</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- E & F & G - Tables dalam Grid -->
    <div class="row">
        <!-- E. Table Group Personil -->
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-users me-1"></i>
                    Personil per Group
                </div>
                <div class="card-body p-1 bg-light">
                    <div class="table-responsive">
                        <div class="form-input">
                            <table class="table border table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>Group</th>
                                        <th class="text-center">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($groupPersonnel as $group)
                                        <tr>
                                            <td>{{ $group['group_name'] }}</td>
                                            <td class="text-center"><strong>{{ $group['total_personnel'] }}</strong></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center">Tidak ada data group</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- F. Table Competency dengan Persentase -->
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-graduation-cap me-1"></i>
                    Personil per Kompetensi
                </div>
                <div class="card-body p-1 bg-light">
                    <div class="table-responsive">
                        <div class="form-input">
                            <table class="table border table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>Kompetensi</th>
                                        <th class="text-center">Jumlah</th>
                                        <th class="text-center">%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($competencyPersonnel as $competency)
                                        <tr>
                                            <td>{{ $competency['competency_name'] }}</td>
                                            <td class="text-center">{{ $competency['total_personnel'] }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-primary">{{ $competency['percentage'] }}%</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">Tidak ada data kompetensi</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- G. Table Employee Type -->
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-briefcase me-1"></i>
                    Personil per Tipe Karyawan
                </div>
                <div class="card-body p-1 bg-light">
                    <div class="table-responsive">
                        <div class="form-input">
                            <table class="table border table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tipe Karyawan</th>
                                        <th class="text-center">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($employeeTypePersonnel as $type)
                                        <tr>
                                            <td>{{ $type['employee_type_name'] }}</td>
                                            <td class="text-center"><strong>{{ $type['total_personnel'] }}</strong></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center">Tidak ada data tipe karyawan</td>
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
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script>
        // Chart Komposisi Kompetensi
        const competencyData = @json($competencyChart);
        
        const competencyCtx = document.getElementById('competencyChart').getContext('2d');
        const competencyChart = new Chart(competencyCtx, {
            type: 'doughnut',
            data: {
                labels: competencyData.labels,
                datasets: [{
                    data: competencyData.data,
                    backgroundColor: [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', 
                        '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    title: {
                        display: true,
                        text: 'Distribusi Kompetensi Personil'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    </script>
@endpush