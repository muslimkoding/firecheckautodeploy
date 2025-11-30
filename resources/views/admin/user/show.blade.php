@extends('admin.template')

@section('title', 'Detail Data User - ' . $user->name)

@section('breadcrumb')
    <h1 class="mt-4">Detail User</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('user.index') }}" class="text-decoration-none text-secondary">List
                User</a></li>
        <li class="breadcrumb-item ">Detail {{ $user->name }}</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <!-- Profile Card -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="card-title mb-0">
                        <i class="fas fa-id-card me-2"></i>Profile User
                    </div>
                    <div class="btn-group">
                        <a href="{{ route('user.index') }}" class="btn btn-outline-gray btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                        @can('user.update')
                        <a href="{{ route('user.edit', $user->id) }}" class="btn btn-gray btn-sm">
                            <i class="fas fa-edit me-1"></i> Edit
                        </a>
                        @endcan
                    </div>
                </div>
                <div class="card-body p-1 text-center bg-light">
                    <div class="form-input">
                        <!-- Profile Photo -->
                    <div class="position-relative d-inline-block mb-3">
                        @if ($user->image)
                            <img src="{{ asset('storage/' . $user->image) }}" alt="{{ $user->name }}"
                                class="img-thumbnail rounded-circle shadow"
                                style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <div class="bg-gradient-secondary rounded-circle d-flex align-items-center justify-content-center shadow"
                                style="width: 150px; height: 150px;">
                                <i class="fas fa-user fa-3x text-white"></i>
                            </div>
                        @endif
                        <div class="position-absolute bottom-0 end-0 bg-success rounded-circle border border-3 border-white"
                            style="width: 20px; height: 20px;"></div>
                    </div>

                    <!-- User Info -->
                    <h4 class="mb-1">{{ $user->name }}</h4>
                    <p class="text-muted mb-2">{{ $user->email }}</p>

                    @if ($user->nip)
                        <div class="badge bg-info mb-3">
                            <i class="fas fa-id-badge me-1"></i>NIP: {{ $user->nip }}
                        </div>
                    @endif

                    <!-- Quick Stats -->
                    <div class="row mt-4 text-start">
                        <div class="col-6">
                            <small class="text-muted d-block">Usia</small>
                            <strong>
                                @if ($user->date_birth)
                                    {{ \Carbon\Carbon::parse($user->date_birth)->age }} tahun
                                @else
                                    -
                                @endif
                            </strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">Member sejak</small>
                            <strong>{{ $user->created_at->format('M Y') }}</strong>
                        </div>
                    </div>
                    </div>
                </div>
            </div>

            <!-- Group & Position Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <div class="card-title mb-0">
                        <i class="fas fa-users me-2"></i>Keanggotaan
                    </div>
                </div>
                <div class="card-body p-1 bg-light">
                    <div class="form-input">
                        <div class="mb-3">
                            <small class="text-muted d-block">Grup</small>
                            @if ($user->group)
                                <div class="d-flex align-items-center mt-1">
                                    <i class="fas fa-users text-primary me-2"></i>
                                    <strong>{{ $user->group->name }}</strong>
                                </div>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </div>
    
                        <div class="mb-3">
                            <small class="text-muted d-block">Posisi</small>
                            @if ($user->position)
                                <div class="d-flex align-items-center mt-1">
                                    <i class="fas fa-briefcase text-warning me-2"></i>
                                    <strong>{{ $user->position->name }}</strong>
                                </div>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </div>
    
                        <div>
                            <small class="text-muted d-block">Kompetensi</small>
                            @if ($user->competency)
                                <div class="d-flex align-items-center mt-1">
                                    <i class="fas fa-star text-success me-2"></i>
                                    <strong>{{ $user->competency->name }}</strong>
                                </div>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- Main Information Card -->
            <div class="card mb-4">
                <div class="card-header ">
                    <div class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Informasi Detail
                    </div>
                    
                </div>
                <div class="card-body p-1 bg-light">
                    <div class="form-input">
                        <div class="row">
                            <!-- Personal Information -->
                            <div class="col-md-6">
                                <h6 class="border-bottom pb-2 mb-3 text-primary">
                                    <i class="fas fa-user me-2"></i>Informasi Pribadi
                                </h6>
                                <div class="info-item mb-3">
                                    <small class="text-muted d-block">Nama Lengkap</small>
                                    <div class="d-flex align-items-center mt-1">
                                        <i class="fas fa-signature text-primary me-2"></i>
                                        <strong>{{ $user->name }}</strong>
                                    </div>
                                </div>
                                <div class="info-item mb-3">
                                    <small class="text-muted d-block">Email</small>
                                    <div class="d-flex align-items-center mt-1">
                                        <i class="fas fa-envelope text-primary me-2"></i>
                                        <strong>{{ $user->email }}</strong>
                                    </div>
                                </div>
                                <div class="info-item mb-3">
                                    <small class="text-muted d-block">Tanggal Lahir</small>
                                    <div class="d-flex align-items-center mt-1">
                                        <i class="fas fa-birthday-cake text-primary me-2"></i>
                                        <strong>
                                            @if ($user->date_birth)
                                                {{ \Carbon\Carbon::parse($user->date_birth)->format('d F Y') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </strong>
                                    </div>
                                </div>
                            </div>
    
                            <!-- Employment Information -->
                            <div class="col-md-6">
                                <h6 class="border-bottom pb-2 mb-3 text-success">
                                    <i class="fas fa-briefcase me-2"></i>Informasi Kepegawaian
                                </h6>
                                <div class="info-item mb-3">
                                    <small class="text-muted d-block">Jenis Pegawai</small>
                                    <div class="d-flex align-items-center mt-1">
                                        <i class="fas fa-user-tie text-success me-2"></i>
                                        <strong>
                                            @if ($user->employeType)
                                                {{ $user->employeType->name }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </strong>
                                    </div>
                                </div>
                                <div class="info-item mb-3">
                                    <small class="text-muted d-block">NIP</small>
                                    <div class="d-flex align-items-center mt-1">
                                        <i class="fas fa-id-card text-success me-2"></i>
                                        <strong>
                                            @if ($user->nip)
                                                {{ $user->nip }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </strong>
                                    </div>
                                </div>
                            </div>
                        </div>
    
                        <!-- Timeline -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h6 class="border-bottom pb-2 mb-3 text-info">
                                    <i class="fas fa-history me-2"></i>Riwayat Sistem
                                </h6>
                                <div class="timeline">
                                    <div class="timeline-item d-flex">
                                        <div class="timeline-marker bg-primary rounded-circle me-3">
                                            <i class="fas fa-plus text-white"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <small class="text-muted">Akun dibuat</small>
                                            <div class="fw-bold">{{ $user->created_at->format('d F Y H:i') }}</div>
                                        </div>
                                    </div>
                                    <div class="timeline-item d-flex mt-3">
                                        <div class="timeline-marker bg-warning rounded-circle me-3">
                                            <i class="fas fa-edit text-white"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <small class="text-muted">Terakhir diperbarui</small>
                                            <div class="fw-bold">{{ $user->updated_at->format('d F Y H:i') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Information Card -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Statistik & Aktivitas
                    </h6>
                </div>
                <div class="card-body p-1 bg-light">
                    <div class="form-input">
                        <div class="row text-center">
                            <div class="col-md-4 mb-3">
                                <div class="stat-card p-3 rounded bg-light">
                                    <i class="fas fa-clipboard-check fa-2x text-primary mb-2"></i>
                                    <h4 class="mb-1">{{ $userStats['total_checklists'] }}</h4>
                                    <small class="text-muted">Total Checklist</small>
                                    @if ($userStats['total_checks_this_month'] > 0)
                                        <div class="mt-1">
                                            <small class="text-success">
                                                <i class="fas fa-arrow-up me-1"></i>
                                                {{ $userStats['total_checks_this_month'] }} bulan ini
                                            </small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="stat-card p-3 rounded bg-light">
                                    <i class="fas fa-fire-extinguisher fa-2x text-success mb-2"></i>
                                    <h4 class="mb-1">{{ $userStats['total_apar_checked'] }}</h4>
                                    <small class="text-muted">APAR Diperiksa</small>
                                    @if ($userStats['apar_checks_this_month'] > 0)
                                        <div class="mt-1">
                                            <small class="text-success">
                                                <i class="fas fa-arrow-up me-1"></i>
                                                {{ $userStats['apar_checks_this_month'] }} bulan ini
                                            </small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="stat-card p-3 rounded bg-light">
                                    <i class="fas fa-fire-hydrant fa-2x text-info mb-2"></i>
                                    <h4 class="mb-1">{{ $userStats['total_hydrant_checked'] }}</h4>
                                    <small class="text-muted">Hydrant Diperiksa</small>
                                    @if ($userStats['hydrant_checks_this_month'] > 0)
                                        <div class="mt-1">
                                            <small class="text-success">
                                                <i class="fas fa-arrow-up me-1"></i>
                                                {{ $userStats['hydrant_checks_this_month'] }} bulan ini
                                            </small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
    
                        <!-- Additional Stats -->
                        {{-- <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between align-items-center p-2 border rounded">
                                    <small class="text-muted">Rata-rata per Bulan</small>
                                    <strong class="text-primary">{{ $userStats['average_checks_per_month'] }}</strong>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between align-items-center p-2 border rounded">
                                    <small class="text-muted">Completion Rate</small>
                                    <strong class="text-success">{{ $userStats['completion_rate'] }}%</strong>
                                </div>
                            </div>
                        </div> --}}
    
                        <!-- Last Activity -->
                        @if ($userStats['last_activity'])
                            <div class="text-center mt-3 p-2 bg-light rounded">
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>
                                    Aktivitas terakhir:
                                    {{ $userStats['last_activity']->date_check->format('d F Y H:i') }}
                                </small>
                            </div>
                        @else
                            <div class="text-center mt-2">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Belum ada aktivitas pengecekan
                                </small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .bg-gradient-success {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .bg-gradient-info {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }

        .bg-gradient-warning {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        }

        .bg-gradient-secondary {
            background: linear-gradient(135deg, #868f96 0%, #596164 100%);
        }

        /* .card {
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            transition: all 0.3s;
        } */

        /* .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.2);
        } */

        .info-item {
            padding: 0.5rem 0;
            border-bottom: 1px solid #f8f9fa;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .timeline-marker {
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .stat-card {
            transition: all 0.3s ease;
            border: 1px solid #e3e6f0;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .badge {
            font-size: 0.75em;
            padding: 0.35em 0.65em;
        }

        .img-thumbnail {
            border: 3px solid #fff;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Add smooth animations
        document.addEventListener('DOMContentLoaded', function() {
            // Animate cards on load
            const cards = document.querySelectorAll('.card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';

                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });

            // Add hover effects to info items
            const infoItems = document.querySelectorAll('.info-item');
            infoItems.forEach(item => {
                item.addEventListener('mouseenter', function() {
                    this.style.backgroundColor = '#f8f9fa';
                    this.style.borderRadius = '0.375rem';
                    this.style.paddingLeft = '0.75rem';
                });

                item.addEventListener('mouseleave', function() {
                    this.style.backgroundColor = '';
                    this.style.borderRadius = '';
                    this.style.paddingLeft = '';
                });
            });
        });
    </script>
@endpush

{{-- @extends('admin.template')

@section('title', 'Detail Data User')

@section('breadcrumb')
    <h1 class="mt-4">Detail User</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('user.index') }}" class="text-decoration-none text-secondary">List</a></li>
        <li class="breadcrumb-item">Detail</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('user.index') }}" class="text-decoration-none text-dark">
                            <i class="fa-solid fa-arrow-left"></i> Back
                        </a>
                        <div class="btn-group">
                            <a href="{{ route('user.edit', $user->id) }}" class="btn btn-warning btn-sm">
                                <i class="fa-solid fa-edit"></i> Edit
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body p-1 bg-light">
                    <div class="row">
                        <!-- Foto Profil -->
                        <div class="col-md-3 text-center mb-4">
                            <div class="border rounded p-3 bg-white">
                                @if ($user->image)
                                    <img src="{{ asset('storage/' . $user->image) }}" 
                                         alt="{{ $user->name }}" 
                                         class="img-fluid rounded mb-2" 
                                         style="max-height: 200px;">
                                    <small class="text-muted d-block">Foto Profil</small>
                                @else
                                    <div class="bg-secondary rounded d-flex align-items-center justify-content-center" 
                                         style="height: 200px;">
                                        <i class="fa-solid fa-user fa-3x text-white"></i>
                                    </div>
                                    <small class="text-muted d-block mt-2">Tidak ada foto</small>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Data User -->
                        <div class="col-md-9">
                            <div class="table-responsive">
                                <table class="table border mb-0 align-middle bg-white">
                                    <tr>
                                        <th width="30%">Nama Lengkap</th>
                                        <td>{{ $user->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td>{{ $user->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>NIP</th>
                                        <td>
                                            @if ($user->nip)
                                                {{ $user->nip }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal Lahir</th>
                                        <td>
                                            @if ($user->date_birth)
                                                {{ \Carbon\Carbon::parse($user->date_birth)->format('d F Y') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Jenis Pegawai</th>
                                        <td>
                                            @if ($user->employeType)
                                                {{ $user->employeType->name }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Grup</th>
                                        <td>
                                            @if ($user->group)
                                                {{ $user->group->name }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Posisi</th>
                                        <td>
                                            @if ($user->position)
                                                {{ $user->position->name }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Kompetensi</th>
                                        <td>
                                            @if ($user->competency)
                                                {{ $user->competency->name }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Usia</th>
                                        <td>
                                            @if ($user->date_birth)
                                                {{ \Carbon\Carbon::parse($user->date_birth)->age }} tahun
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal Dibuat</th>
                                        <td>{{ $user->created_at->format('d F Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Terakhir Diupdate</th>
                                        <td>{{ $user->updated_at->format('d F Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection --}}
