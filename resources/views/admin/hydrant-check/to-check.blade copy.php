@extends('admin.template')

@section('title', 'Hydrant to Check')

@section('breadcrumb')
    <h1 class="mt-4">Checklist Hydrant</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item">List Hydrant</li>
    </ol>
@endsection

@section('content')
<div class="row">
    <div class="col-12">

        @if(session('info'))
            <div class="alert alert-info">
                {{ session('info') }}
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if($assignedZones->count() > 0)
            <div class="alert alert-success">
                <h6><i class="fas fa-map-marker-alt"></i> Zona yang Ditugaskan:</h6>
                @foreach($assignedZones as $zone)
                    <span class="badge bg-primary me-1">{{ $zone->name }}</span>
                @endforeach
            </div>
        @endif

        <!-- Filter & Search Section -->
        <div class="row mb-4">
            <div class="col-md-6">
                <!-- Filter Buttons -->
                <div class="btn-group" role="group">
                    <a href="{{ route('hydrant-check.to-check', ['filter' => '']) }}" 
                       class="btn btn-primary {{ empty($filter) ? 'active-check' : '' }}">
                        Semua ({{ $totalHydrants }})
                    </a>
                    <a href="{{ route('hydrant-check.to-check', ['filter' => 'checked']) }}" 
                       class="btn btn-success {{ $filter === 'checked' ? 'active-check' : '' }}">
                        Sudah Dicek ({{ $checkedCount }})
                    </a>
                    <a href="{{ route('hydrant-check.to-check', ['filter' => 'unchecked']) }}" 
                       class="btn btn-warning {{ $filter === 'unchecked' ? 'active-check' : '' }}">
                        Belum Dicek ({{ $uncheckedCount }})
                    </a>
                </div>
            </div>
            <div class="col-md-6 mt-4 mt-md-0">
                <!-- Search Form -->
                <form action="{{ route('hydrant-check.to-check') }}" method="GET" class="d-flex">
                    <input type="hidden" name="filter" value="{{ $filter }}">
                    <input type="text" name="search" class="form-control me-2" 
                           placeholder="Cari nomor hydrant, lokasi, zona..." 
                           value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                    @if(request('search'))
                        <a href="{{ route('hydrant-check.to-check', ['filter' => $filter]) }}" 
                           class="btn btn-secondary ms-2">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-fire-extinguisher me-2 text-primary"></i>
                        Total Hydrant
                    </div>
                    <div class="card-body p-1 bg-light text-center">
                        <div class="form-input">
                            <h4>{{ $totalHydrants }}</h4>
                            <p>Total Hydrant<br><small>Bulan Ini</small></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card mt-4 mt-md-0">
                    <div class="card-header">
                        <i class="fas fa-check-circle me-2 text-success"></i> Sudah Dicek
                    </div>
                    <div class="card-body text-center p-1 bg-light">
                        <div class="form-input">
                            <h4>{{ $checkedCount }}</h4>
                            <p>Sudah Dicek<br><small>Bulan Ini</small></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card mt-4 mt-md-0">
                    <div class="card-header">
                        <i class="fas fa-clock me-2 text-warning"></i> Belum Dicek
                    </div>
                    <div class="card-body text-center p-1 bg-light">
                        <div class="form-input">
                            <h4>{{ $uncheckedCount }}</h4>
                            <p>Belum Dicek<br><small>Bulan Ini</small></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card mt-4 mt-md-0">
                    <div class="card-header">
                        <i class="fas fa-chart-line me-2 text-info"></i> Progress
                    </div>
                    <div class="card-body text-center p-1 bg-light">
                        <div class="form-input">
                            <h4>{{ $progress }}%</h4>
                            <p>Progress<br><small>Bulan Ini</small></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Bar -->
        <!-- <div class="progress mt-3 mb-3" style="height: 25px;">
            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" 
                 role="progressbar" 
                 style="width: {{ $progress }}%"
                 aria-valuenow="{{ $progress }}" 
                 aria-valuemin="0" 
                 aria-valuemax="100">
                {{ $progress }}% Complete
            </div>
        </div> -->

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-fire-hydrant"></i> Hydrant yang Perlu Diperiksa
                    <small class="text-muted">(Bulan {{ now()->translatedFormat('F Y') }})</small>
                </h5>
            </div>
            <div class="card-body p-1 bg-light">
                
                @if($hydrants->count() > 0)
                    <div class="table-responsive">
                        <table class="table border mb-0 align-middle bg-white table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>
                                        <a href="{{ route('hydrant-check.to-check', [
                                            'filter' => $filter,
                                            'search' => request('search'),
                                            'sort' => 'number_hydrant', 
                                            'direction' => $sort === 'number_hydrant' && $direction === 'asc' ? 'desc' : 'asc'
                                        ]) }}" class="text-decoration-none text-dark">
                                            Nomor Hydrant
                                            @if($sort === 'number_hydrant')
                                                <i class="fas fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
                                            @else
                                                <i class="fas fa-sort"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>Zona</th>
                                    <th>Lokasi</th>
                                    <th>Gedung</th>
                                    <th>Lantai</th>
                                    <th>Tipe Hydrant</th>
                                    <th>Status Bulan Ini</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($hydrants as $hydrant)
                                <tr class="{{ $hydrant->is_checked_this_month ? 'table-success' : 'table-warning' }}">
                                    <td>{{ $loop->iteration + ($hydrants->currentPage() - 1) * $hydrants->perPage() }}</td>
                                    <td>
                                        <strong>{{ $hydrant->number_hydrant }}</strong>
                                        @if($hydrant->is_checked_this_month)
                                            <br><small class="text-success">
                                                <i class="fas fa-check-circle"></i> Sudah diperiksa bulan ini
                                            </small>
                                        @else
                                            <br><small class="text-warning">
                                                <i class="fas fa-clock"></i> Belum diperiksa bulan ini
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $hydrant->zone->name }}</span>
                                    </td>
                                    <td>{{ $hydrant->location }}</td>
                                    <td>{{ $hydrant->building->name ?? '-' }}</td>
                                    <td>{{ $hydrant->floor->name ?? '-' }}</td>
                                    <td>{{ $hydrant->hydrantType->name ?? '-' }}</td>
                                    <td>
                                        @if($hydrant->is_checked_this_month)
                                            <span class="badge bg-success">
                                                <i class="fas fa-check"></i> Sudah
                                            </span>
                                        @else
                                            <span class="badge bg-warning">
                                                <i class="fas fa-times"></i> Belum
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($hydrant->is_checked_this_month && $hydrant->latest_check_id)
                                            <a href="{{ route('hydrant-check.edit', $hydrant->latest_check_id) }}" 
                                               class="btn btn-info btn-sm">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                        @else
                                            <a href="{{ route('hydrant-check.create', $hydrant->id) }}" 
                                               class="btn btn-primary btn-sm">
                                                <i class="fas fa-clipboard-check"></i> Checklist
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-fire-hydrant fa-3x text-muted mb-3"></i>
                        <h4>Tidak Ada Hydrant</h4>
                        <p class="text-muted">
                            @if(request('search'))
                                Tidak ada hydrant yang sesuai dengan pencarian "{{ request('search') }}"
                            @elseif($filter === 'checked')
                                Tidak ada hydrant yang sudah dicek bulan ini.
                            @elseif($filter === 'unchecked')
                                Selamat! Semua hydrant sudah dicek bulan ini.
                            @else
                                Tidak ada hydrant yang perlu diperiksa di zona Anda.
                            @endif
                        </p>
                        @if(request('search') || $filter)
                            <a href="{{ route('hydrant-check.to-check') }}" class="btn btn-primary">
                                <i class="fas fa-refresh"></i> Tampilkan Semua
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3 mb-4">
            <div class="text-muted">
                Menampilkan {{ $hydrants->firstItem() }} sampai {{ $hydrants->lastItem() }} 
                dari {{ $hydrants->total() }} data
                @if($filter)
                    (Filter: {{ $filter === 'checked' ? 'Sudah Dicek' : 'Belum Dicek' }})
                @endif
            </div>
            <nav>
                {{ $hydrants->appends([
                    'filter' => $filter,
                    'search' => request('search'),
                    'sort' => $sort,
                    'direction' => $direction
                ])->links() }}
            </nav>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.active-check {
    font-weight: bold;
    box-shadow: 0 0 0 2px rgba(0,123,255,.25);
}
</style>
@endpush