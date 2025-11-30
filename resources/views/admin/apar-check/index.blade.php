@extends('admin.template')

@section('title', 'Riwayat Pengecekan APAR')

@section('breadcrumb')
    <h1 class="mt-4">Riwayat Pengecekan APAR</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('apar-check.index') }}" class="text-decoration-none text-secondary">Summary</a></li>
        <li class="breadcrumb-item ">Riwayat Pengecekan APAR</li>
    </ol>
@endsection

@section('content')
<div class="card mb-4">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                {{-- <a href="{{ route('master.index') }}" class="text-decoration-none text-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a> --}}
                <i class="fa-solid fa-table-list"></i> Summary Data APAR

            </div>
            <div class="d-flex gap-2">
                {{-- <button type="button" class="btn btn-success btn-sm" id="exportExcel">
                    <i class="fas fa-file-excel"></i> Export
                </button>
                <button type="button" class="btn btn-info btn-sm" id="printData">
                    <i class="fas fa-print"></i> Print
                </button> --}}
                <a href="{{ route('apar-check.scan') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-clipboard-check"></i> Pengecekan Baru
                </a>
            </div>
        </div>
    </div>

    <div class="card-body p-1 bg-light">
        <!-- Filter Section -->
        <div class="p-2">
            <div class="row mb-3">
                {{-- <div class="col-md-3">
                    <label class="form-label">Nomor APAR</label>
                    <select class="form-select form-select-sm" id="apar_id">
                        <option value="">Semua APAR</option>
                        @foreach($apars as $apar)
                            <option value="{{ $apar->id }}">{{ $apar->number_apar }}</option>
                        @endforeach
                    </select>
                </div> --}}
                <div class="col-md-4">
                    <label class="form-label">Zona</label>
                    <select class="form-select form-select-sm" id="zone_id">
                        <option value="">Semua Zona</option>
                        @foreach($zones as $zone)
                            <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Gedung</label>
                    <select class="form-select form-select-sm" id="building_id">
                        <option value="">Semua Gedung</option>
                        @foreach($buildings as $building)
                            <option value="{{ $building->id }}">{{ $building->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Group</label>
                    <select class="form-select form-select-sm" id="group_id">
                        <option value="">Semua Group</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                {{-- <div class="col-md-3">
                    <label class="form-label">Pemeriksa</label>
                    <select class="form-select form-select-sm" id="user_id">
                        <option value="">Semua Pemeriksa</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div> --}}
                <div class="col-md-4">
                    <label class="form-label">Kondisi APAR</label>
                    <select class="form-select form-select-sm" id="condition_id">
                        <option value="">Semua Kondisi</option>
                        @foreach($conditions as $condition)
                            <option value="{{ $condition->id }}">{{ $condition->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Pengecekan</label>
                    <input type="date" class="form-control form-control-sm" id="date_check">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Rentang Tanggal</label>
                    <div class="input-group input-group-sm">
                        <input type="date" class="form-control" id="date_range_start" placeholder="Dari">
                        <input type="date" class="form-control" id="date_range_end" placeholder="Sampai">
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Pencarian</label>
                    <input type="text" class="form-control form-control-sm" id="search" placeholder="Cari nomor APAR, lokasi, pemeriksa...">
                </div>
                <div class="col-md-6 d-flex align-items-end mt-2 mt-md-0">
                    <button type="button" class="btn btn-sm btn-primary me-2" id="applyFilter">
                        <i class="fas fa-filter"></i> Terapkan Filter
                    </button>
                    <button type="button" class="btn btn-sm btn-secondary" id="resetFilter">
                        <i class="fas fa-refresh"></i> Reset
                    </button>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <div class="form-input">
                <table class="table border w-100 bg-white" id="apar-checks-table">
                    <thead>
                        <tr class="align-middle">
                            <th class="text-center" style="width: 4rem;">#</th>
                            <th>Tanggal</th>
                            <th>Nomor APAR</th>
                            <th>Lokasi</th>
                            <th>Pemeriksa</th>
                            <th>Zona</th>
                            <th>Gedung</th>
                            <th>Group</th>
                            <th>Kondisi</th>
                            <th>Tekanan</th>
                            <th>Tabung</th>
                            <th>Pin Seal</th>
                            <th>Selang</th>
                            <th>Handle</th>
                            {{-- <th>Detail Pengecekan</th> --}}
                            <th class="text-center" style="width: 10rem;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data akan di-load secara otomatis oleh DataTables -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">
<style>
    .badge { font-size: 0.75em; }
    .table-responsive { overflow-x: auto; }
</style>
@endpush

@push('scripts')
<script src="{{ asset('assets/sweetalert.js') }}"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

<script>
$(document).ready(function() {
    var table = $('#apar-checks-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: "{{ route('apar-check.index') }}",
            data: function(d) {
                d.apar_id = $('#apar_id').val();
                d.date_check = $('#date_check').val();
                d.date_range_start = $('#date_range_start').val();
                d.date_range_end = $('#date_range_end').val();
                d.zone_id = $('#zone_id').val();
                d.building_id = $('#building_id').val();
                d.group_id = $('#group_id').val();
                d.user_id = $('#user_id').val();
                d.condition_id = $('#condition_id').val();
                d.search = $('#search').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', className: 'text-center', orderable: false, searchable: false },
            { data: 'formatted_date', className: 'text-center' },
            { data: 'apar_number' },
            { data: 'apar_location' },
            { data: 'user_name' },
            { data: 'zone_name' },
            { data: 'building_name' },
            { data: 'group_name' },
            { data: 'condition_badge', className: 'text-center' },
            { data: 'pressure_name' },
            { data: 'cylinder_name' },
            { data: 'pin_seal_name' },
            { data: 'hose_name' },
            { data: 'handle_name' },
            // { data: 'check_details' },
            { data: 'action', className: 'text-center', orderable: false, searchable: false }
        ],
        order: [[1, 'desc']], // Default order by tanggal descending
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
        // dom: '<"row mb-3"<"col-md-6"l><"col-md-6"B>>' +
        //      '<"row"<"col-md-12"tr>>' +
        //      '<"row"<"col-md-5"i><"col-md-7"p>>',
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                     '<"row"<"col-sm-12"tr>>' +
                     '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>' +
                     '<"row"<"col-sm-12"B>>',
        buttons: [
            {
                extend: 'excel',
                className: 'btn btn-success btn-sm',
                text: '<i class="fas fa-file-excel"></i> Excel',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]
                }
            },
            // {
            //     extend: 'pdf',
            //     className: 'btn btn-danger btn-sm',
            //     text: '<i class="fas fa-file-pdf"></i> PDF',
            //     exportOptions: {
            //         columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]
            //     }
            // }
        ]
    });

    // Apply filter
    $('#applyFilter').on('click', function() {
        table.ajax.reload();
    });

    // Reset filter
    $('#resetFilter').on('click', function() {
        $('#apar_id, #zone_id, #building_id, #group_id, #user_id, #condition_id').val('');
        $('#date_check, #date_range_start, #date_range_end, #search').val('');
        table.ajax.reload();
    });

    // Real-time search with debounce
    var searchTimeout;
    $('#search').on('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            table.ajax.reload();
        }, 500); // Wait 500ms after user stops typing
    });

    // Export buttons
    $('#exportExcel').on('click', function() {
        table.button('.buttons-excel').trigger();
    });

    $('#printData').on('click', function() {
        table.button('.buttons-print').trigger();
    });
});

// SweetAlert confirm delete
function confirmDelete(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data pengecekan APAR yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#999999',
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + id).submit();
        }
    });
}
</script>
@endpush