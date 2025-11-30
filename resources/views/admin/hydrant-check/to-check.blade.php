@extends('admin.template')

@section('title', 'Hydrant to Check')

@section('breadcrumb')
    <h1 class="mt-4">Checklist Hydrant</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item">List Hydrant</li>
    </ol>
@endsection

@push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <style>
        .active-check {
            font-weight: bold;
            border: 2px solid #0d6efd !important;
        }
        .dataTables_wrapper .row {
            margin: 10px 0;
        }
        table.dataTable tbody tr {
            cursor: pointer;
        }
        /* Custom search box */
        .custom-search-box {
            max-width: 300px;
        }
        /* Pagination di tengah */
        .dataTables_paginate {
            text-align: center !important;
        }
        .pagination {
            justify-content: center !important;
        }
    </style>
@endpush

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
                <!-- Custom Search Box -->
                <div class="custom-search-box">
                    <div class="input-group">
                        <input type="text" id="custom-search-input" class="form-control" 
                               placeholder="Cari nomor hydrant, lokasi, zona...">
                        <button class="btn btn-outline-secondary" type="button" id="clear-search">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <small class="text-muted mt-1 d-block">
                        <i class="fas fa-info-circle"></i> Pencarian real-time
                    </small>
                </div>
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
        <div class="progress mt-3 mb-3" style="height: 25px;">
            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" 
                 role="progressbar" 
                 style="width: {{ $progress }}%"
                 aria-valuenow="{{ $progress }}" 
                 aria-valuemin="0" 
                 aria-valuemax="100">
                {{ $progress }}% Complete
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-fire-hydrant"></i> Hydrant yang Perlu Diperiksa
                    <small class="text-muted">(Bulan {{ now()->translatedFormat('F Y') }})</small>
                </h5>
            </div>
            <div class="card-body p-1 bg-light">
                <div class="table-responsive">
                    <div class="form-input">
                        <table class="table border table-striped w-100" id="hydrant-table">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Nomor Hydrant</th>
                                    <th>Zona</th>
                                    <th>Lokasi</th>
                                    <th>Gedung</th>
                                    <th>Lantai</th>
                                    <th>Tipe Hydrant</th>
                                    <th width="10%">Status</th>
                                    <th width="12%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data akan di-load oleh DataTables -->
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
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
    $(document).ready(function() {
        // Get current filter from URL
        const urlParams = new URLSearchParams(window.location.search);
        const currentFilter = urlParams.get('filter') || '';

        // Initialize DataTables
        var table = $('#hydrant-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('hydrant-check.to-check') }}",
                data: function (d) {
                    d.filter = currentFilter;
                }
            },
            columns: [
                {
                    data: 'no',
                    name: 'no',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                },
                {
                    data: 'number_hydrant',
                    name: 'number_hydrant',
                    render: function(data, type, row) {
                        return '<strong>' + data + '</strong><br>' + row.status_info;
                    }
                },
                {
                    data: 'zone_name',
                    name: 'zone.name'
                },
                {
                    data: 'location',
                    name: 'location'
                },
                {
                    data: 'building_name',
                    name: 'building.name'
                },
                {
                    data: 'floor_name',
                    name: 'floor.name'
                },
                {
                    data: 'hydrant_type_name',
                    name: 'hydrantType.name'
                },
                {
                    data: 'status_badge',
                    name: 'status_badge',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                }
            ],
            order: [[1, 'asc']], // Default order by nomor hydrant
            language: {
                processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div> Memproses...',
                search: 'Cari:',
                lengthMenu: 'Tampilkan _MENU_ data',
                info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                infoEmpty: 'Menampilkan 0 sampai 0 dari 0 data',
                infoFiltered: '(disaring dari _MAX_ total data)',
                zeroRecords: 'Data tidak ditemukan',
                emptyTable: 'Tidak ada data hydrant yang perlu diperiksa',
                paginate: {
                    first: 'Pertama',
                    previous: 'Sebelumnya',
                    next: 'Selanjutnya',
                    last: 'Terakhir'
                }
            },
            // Page length options
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            drawCallback: function() {
                // Add row numbers
                var api = this.api();
                var startIndex = api.page.info().start;
                
                api.column(0, {page: 'current'}).nodes().each(function(cell, i) {
                    cell.innerHTML = startIndex + i + 1;
                });
            },
            initComplete: function() {
                // Hide default search box karena kita pakai custom
                $('.dataTables_filter').hide();
            }
        });

        // Custom search functionality - REAL TIME
        $('#custom-search-input').on('keyup', function() {
            table.search(this.value).draw();
        });

        // Clear search
        $('#clear-search').on('click', function() {
            $('#custom-search-input').val('');
            table.search('').draw();
        });

        // Auto refresh data every 30 seconds
        // setInterval(function() {
        //     table.ajax.reload(null, false);
        // }, 30000);
    });
    </script>
@endpush