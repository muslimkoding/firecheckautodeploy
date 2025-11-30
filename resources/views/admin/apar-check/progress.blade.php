@extends('admin.template')

@section('title', 'Progress Pengecekan Hari Ini')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-chart-line"></i> Progress Pengecekan Hari Ini
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

                <div class="row text-center">
                    <div class="col-md-4">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h1 class="display-4">{{ $totalApars }}</h1>
                                <h5>Total APAR</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h1 class="display-4">{{ $checkedApars }}</h1>
                                <h5>Telah Diperiksa</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <h1 class="display-4">{{ $progress }}%</h1>
                                <h5>Progress</h5>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="progress mt-4" style="height: 30px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" 
                         role="progressbar" 
                         style="width: {{ $progress }}%"
                         aria-valuenow="{{ $progress }}" 
                         aria-valuemin="0" 
                         aria-valuemax="100">
                        {{ $progress }}%
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <a href="{{ route('apar-check.unchecked') }}" class="btn btn-warning btn-lg w-100">
                            <i class="fas fa-clock"></i> Lihat APAR Belum Diperiksa
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('apar-check.to-check') }}" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-list"></i> Lihat Semua APAR
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection