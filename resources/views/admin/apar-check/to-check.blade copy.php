@extends('admin.template')

@section('title', 'APAR to Check')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-clipboard-list"></i> APAR yang Perlu Diperiksa
                </h5>
            </div>
            <div class="card-body">
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

                @if($apars->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Nomor APAR</th>
                                    <th>Zona</th>
                                    <th>Lokasi</th>
                                    <th>Gedung</th>
                                    <th>Lantai</th>
                                    <th>Merek</th>
                                    <th>Tipe</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($apars as $apar)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $apar->number_apar }}</strong>
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
                                        <span class="badge bg-success">Aktif</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('apar-check.create', $apar->id) }}" 
                                           class="btn btn-primary btn-sm">
                                            <i class="fas fa-clipboard-check"></i> Checklist
                                        </a>
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
                        <p class="text-muted">Tidak ada APAR yang perlu diperiksa di zona Anda.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection