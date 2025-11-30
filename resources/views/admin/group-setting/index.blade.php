@extends('admin.template')

@section('title', 'Users Group')

@section('breadcrumb')
    <h1 class="mt-4">Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item">Config Group</li>
    </ol>
@endsection

@section('content')

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="">List Data</div>
                        {{-- <a href="{{ route('user_create') }}" class="btn btn-sm btn-primary"><i
                class="fa-solid fa-square-plus"></i></a> --}}
                    </div>
                </div>
                <div class="card-body p-1 bg-light">
                    <!-- Filter Section -->
                    <div class="row mb-3 p-2">
                        <div class="col-md-4">
                            <label for="group_filter" class="form-label">Filter by Group:</label>
                            <select name="group_filter" id="group_filter" class="form-select form-select-sm">
                                <option value="">Semua Group</option>
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                @endforeach
                                <option value="without_group">- Tanpa Group -</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end mt-2 sm-mt-0">
                            <button type="button" id="btn_reset_filter" class="btn btn-secondary btn-sm">
                                <i class="fas fa-refresh"></i> Reset
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <div class="form-input">
                            <table class="table border mb-0" id="datatable">
                                <thead class="fw-semibold text-nowrap">
                                    <tr class="align-middle">
                                        <th class="text-center">No</th>
                                        <th>Nama</th>
                                        <th>Group</th>
                                        <th>Aksi</th>
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

@push('styles')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
@endpush

@push('scripts')
    <script src="{{ asset('assets/sweetalert.js') }}"></script>

    <!-- DataTables & Plugins -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTables
            var table = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('user.group') }}",
                    type: 'GET',
                    data: function(d) {
                        d.group_filter = $('#group_filter').val(); // Kirim filter ke server
                    },
                    error: function(xhr, error, thrown) {
                        console.log('AJAX Error:', xhr.responseText);
                        alert('Terjadi kesalahan saat memuat data. Lihat console untuk detail.');
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'name',
                        name: 'name',
                    },
                    {
                        data: 'group',
                        name: 'group',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                ],
                language: {
                    processing: "Memproses...",
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                    infoFiltered: "(disaring dari _MAX_ total data)",
                    zeroRecords: "Tidak ada data yang ditemukan",
                    emptyTable: "Tidak ada data tersedia",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                },
                responsive: true,
            });

            // Filter by group
            $('#group_filter').change(function() {
                table.ajax.reload(); // Reload data dengan filter baru
            });

            // Reset filter
            $('#btn_reset_filter').click(function() {
                $('#group_filter').val('');
                table.ajax.reload();
            });
        });
    </script>
@endpush

{{-- @extends('admin.template')

@section('title', 'Users Group')

@section('breadcrumb')
    <h1 class="mt-4">Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item ">Group Setting</li>
    </ol>
@endsection

@section('content')

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <div class="">List Data</div>
                        
                    </div>
                </div>
                <div class="card-body p-1 bg-light">
                    <div class="table-responsive">
                        <div class="form-input">
                            <table class="table border mb-0" id="datatable">
                                <thead class="fw-semibold text-nowrap">
                                    <tr class="align-middle">
                                        <th class=" text-center">No</th>
                                        <th>Nama</th>
                                        <th>Group</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">

    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
@endpush

@push('scripts')
    <script src="{{ asset('assets/sweetalert.js') }}"></script>

    <!-- DataTables & Plugins -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('user.group') }}",
                    type: 'GET',
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'name',
                        name: 'name',
                    },
                    {
                        data: 'group',
                        name: 'group',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                ],
                language: {
                    processing: "Memproses...",
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                    infoFiltered: "(disaring dari _MAX_ total data)",
                    zeroRecords: "Tidak ada data yang ditemukan",
                    emptyTable: "Tidak ada data tersedia",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                },
                responsive: true,
            });
        });
    </script>
@endpush --}}
