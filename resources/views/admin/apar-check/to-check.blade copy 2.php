@extends('admin.template')

@section('title', 'APAR to Check')

@section('breadcrumb')
    <h1 class="mt-4">Checklist</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item">List</li>
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
                            <a href="{{ route('apar-check.to-check', ['filter' => '']) }}" 
                               class="btn btn-primary {{ empty($filter) ? 'active-check' : '' }}">
                                Semua ({{ $totalApars }})
                            </a>
                            <a href="{{ route('apar-check.to-check', ['filter' => 'checked']) }}" 
                               class="btn btn-success {{ $filter === 'checked' ? 'active-check' : '' }}">
                                Sudah Dicek ({{ $checkedCount }})
                            </a>
                            <a href="{{ route('apar-check.to-check', ['filter' => 'unchecked']) }}" 
                               class="btn btn-warning {{ $filter === 'unchecked' ? 'active-check' : '' }}">
                                Belum Dicek ({{ $uncheckedCount }})
                            </a>
                        </div>
                    </div>
                    <div class="col-md-6 mt-4 mt-md-0">
                        <!-- Search Form -->
                        <form action="{{ route('apar-check.to-check') }}" method="GET" class="d-flex">
                            <input type="hidden" name="filter" value="{{ $filter }}">
                            <input type="text" name="search" class="form-control me-2" 
                                   placeholder="Cari nomor APAR, lokasi, zona..." 
                                   value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                            @if(request('search'))
                                <a href="{{ route('apar-check.to-check', ['filter' => $filter]) }}" 
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
                        <div class="card ">
                            <div class="card-header">
                                <i class="fas fa-info-circle me-2 text-primary">
                                </i>
                                Total Apar
                            </div>
                            <div class="card-body p-1 bg-light text-center">
                                <div class="form-input">
                                    
                                        <h4>{{ $totalApars }}</h4>
                                        <p>Total APAR<br><small>Bulan Ini</small></p>
                                    
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
                                    <h4>{{ $checkedCount }}</h4>
                                <p>Sudah Dicek<br><small>Bulan Ini</small></p>
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
                                    <h4>{{ $uncheckedCount }}</h4>
                                <p>Belum Dicek<br><small>Bulan Ini</small></p>
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
                    <i class="fas fa-clipboard-list"></i> APAR yang Perlu Diperiksa
                    <small class="text-muted">(Bulan {{ now()->translatedFormat('F Y') }})</small>
                </h5>
                
                <!-- Download Button -->
                {{-- <div class="btn-group">
                    <a href="{{ route('apar-check.download', ['filter' => $filter]) }}" 
                       class="btn btn-success">
                        <i class="fas fa-file-excel"></i> Download Excel
                    </a>
                </div> --}}
            </div>
            <div class="card-body p-1 bg-light">
                

                @if($apars->count() > 0)
                    <div class="table-responsive">
                        <table class="table border mb-0 align-middle bg-white table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>
                                        <a href="{{ route('apar-check.to-check', [
                                            'filter' => $filter,
                                            'search' => request('search'),
                                            'sort' => 'number_apar', 
                                            'direction' => $sort === 'number_apar' && $direction === 'asc' ? 'desc' : 'asc'
                                        ]) }}" class="text-decoration-none text-dark">
                                            # 
                                            @if($sort === 'number_apar')
                                                <i class="fas fa-sort-{{ $direction === 'asc' ? 'up' : 'down' }}"></i>
                                            @else
                                                <i class="fas fa-sort"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>Nomor APAR</th>
                                    <th>Zona</th>
                                    <th>Lokasi</th>
                                    <th>Gedung</th>
                                    <th>Lantai</th>
                                    <th>Merek</th>
                                    <th>Tipe</th>
                                    <th>Status Bulan Ini</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($apars as $apar)
                                <tr class="{{ $apar->is_checked_this_month ? 'table-success' : 'table-warning' }}">
                                    <td>{{ $loop->iteration + ($apars->currentPage() - 1) * $apars->perPage() }}</td>
                                    <td>
                                        <strong>{{ $apar->number_apar }}</strong>
                                        @if($apar->is_checked_this_month)
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
                                        <span class="badge bg-info">{{ $apar->zone->name }}</span>
                                    </td>
                                    <td>{{ $apar->location }}</td>
                                    <td>{{ $apar->building->name }}</td>
                                    <td>{{ $apar->floor->name }}</td>
                                    <td>{{ $apar->brand->name }}</td>
                                    <td>{{ $apar->aparType->name }}</td>
                                    <td>
                                        @if($apar->is_checked_this_month)
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
                                        @if($apar->is_checked_this_month && $apar->latest_check_id)
                                            <a href="{{ route('apar-check.edit', $apar->latest_check_id) }}" 
                                               class="btn btn-info btn-sm">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                        @else
                                            <a href="{{ route('apar-check.create', $apar->id) }}" 
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
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h4>Tidak Ada APAR</h4>
                        <p class="text-muted">
                            @if(request('search'))
                                Tidak ada APAR yang sesuai dengan pencarian "{{ request('search') }}"
                            @elseif($filter === 'checked')
                                Tidak ada APAR yang sudah dicek bulan ini.
                            @elseif($filter === 'unchecked')
                                Selamat! Semua APAR sudah dicek bulan ini.
                            @else
                                Tidak ada APAR yang perlu diperiksa di zona Anda.
                            @endif
                        </p>
                        @if(request('search') || $filter)
                            <a href="{{ route('apar-check.to-check') }}" class="btn btn-primary">
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
                Menampilkan {{ $apars->firstItem() }} sampai {{ $apars->lastItem() }} 
                dari {{ $apars->total() }} data
                @if($filter)
                    (Filter: {{ $filter === 'checked' ? 'Sudah Dicek' : 'Belum Dicek' }})
                @endif
            </div>
            <nav>
                {{ $apars->appends([
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