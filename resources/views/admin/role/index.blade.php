@extends('admin.template')

@section('title', 'Manajemen Role')

@section('breadcrumb')
    <h1 class="mt-4">Manajemen Role</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('master.index') }}" class="text-decoration-none text-secondary">Master</a></li>
        <li class="breadcrumb-item">Roles</li>
    </ol>
@endsection

@section('content')
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <a href="{{ route('master.index') }}" class="text-decoration-none text-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
                <div class="d-flex gap-2">
                    {{-- <button type="button" class="btn btn-success btn-sm" id="exportExcel">
                        <i class="fas fa-file-excel"></i> Export
                    </button> --}}
                    {{-- <button type="button" class="btn btn-info btn-sm" id="printData">
                        <i class="fas fa-print"></i> Print
                    </button> --}}
                    <a href="{{ route('role.create') }}" class="btn btn-sm btn-light bg-white border">
                        <i class="fas fa-plus"></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body p-1 bg-light">
            <!-- Filter Section -->
            <div class="p-2">
                <div class="row mb-4">
                    <div class="col-md-4">
                        <label class="form-label">Cari Role</label>
                        <input type="text" class="form-control" id="search" placeholder="Cari nama role...">
                    </div>
                    {{-- <div class="col-md-4">
                        <label class="form-label">Group Permission</label>
                        <select class="form-select" id="group_filter">
                            <option value="">Semua Group</option>
                            <option value="user">User</option>
                            <option value="role">Role</option>
                            <option value="permission">Permission</option>
                            <option value="apar">APAR</option>
                            <option value="zone">Zona</option>
                            <option value="building">Gedung</option>
                            <option value="floor">Lantai</option>
                            <option value="brand">Merek</option>
                            <!-- Tambahkan group lainnya sesuai kebutuhan -->
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="button" class="btn btn-primary me-2" id="applyFilter">
                            <i class="fas fa-filter"></i> Terapkan Filter
                        </button>
                        <button type="button" class="btn btn-secondary" id="resetFilter">
                            <i class="fas fa-refresh"></i> Reset
                        </button>
                    </div> --}}
                </div>
            </div>

            <div class="table-responsive" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                <div class="form-input">
                    <table class="table border w-100 bg-white" id="permissions-table">
                        <thead>
                            <tr class="align-middle">
                                <th class="text-center" style="width: 4rem;">#</th>
                                <th>Roles</th>
                                <th>Permissions Count</th>
                                <th>Tanggal Dibuat</th>
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
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <style>
        /* Mobile optimization */
        @media (max-width: 768px) {
            .card-body {
                padding: 0.75rem;
            }
            
            .table-responsive {
                border: 1px solid #dee2e6;
                border-radius: 0.375rem;
                background: white;
            }
            
            .table-responsive::-webkit-scrollbar {
                height: 8px;
            }
            
            .table-responsive::-webkit-scrollbar-thumb {
                background: #c1c1c1;
                border-radius: 4px;
            }
            
            .table-responsive::-webkit-scrollbar-thumb:hover {
                background: #a8a8a8;
            }
        }
        
        /* Desktop styling */
        @media (min-width: 769px) {
            .table-responsive {
                overflow-x: visible;
            }
            
            #permissions-table {
                width: 100% !important;
            }
        }

        /* Badge styling untuk group */
        .badge-group {
            font-size: 0.75em;
            background-color: #6c757d;
            color: white;
        }

        /* Action buttons styling */
        .btn-action {
            padding: 0.25rem 0.5rem;
            margin: 0 0.125rem;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('assets/sweetalert.js') }}"></script>
    
    <!-- DataTables & Plugins -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    
    <!-- DataTables Buttons -->
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    
    <!-- PDF export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

    <script>
        $(document).ready(function() {
            var table = $('#permissions-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('role.index') }}",
                    type: 'GET',
                    data: function(d) {
                        d.search = $('#search').val();
                        d.group_filter = $('#group_filter').val();
                    }
                },
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'name',
                        name: 'name',
                        render: function(data, type, row) {
                            return '<strong class="text-primary">' + data + '</strong>';
                        }
                    },
                    // {
                    //     data: 'group',
                    //     name: 'group',
                    //     render: function(data, type, row) {
                    //         // Extract group from permission name (e.g., "user.create" -> "user")
                    //         var group = data || extractGroup(row.name);
                    //         return '<span class="badge badge-group">' + group + '</span>';
                    //     },
                    //     orderable: false,
                    //     searchable: false
                    // },
                    {
                        data: 'permission_count',
                        name: 'permission_count'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        render: function(data, type, row) {
                            return data ? formatDate(data) : '<span class="text-muted">-</span>';
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ],
                language: {
                    processing: "Memproses...",
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
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
                // order: [[1, 'asc']], // Default order by nama permission
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
                drawCallback: function(settings) {
                    // Update tooltips setelah table redraw
                    $('[data-bs-toggle="tooltip"]').tooltip();
                },
                // PERBAIKAN: Konfigurasi DOM yang benar dengan length menu
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
                            columns: [0, 1, 2, 3]
                        }
                    }
                ]
            });
    
            // Apply filter
            $('#applyFilter').on('click', function() {
                table.draw();
            });
    
            // Reset filter
            $('#resetFilter').on('click', function() {
                $('#search').val('');
                $('#group_filter').val('');
                table.draw();
            });
    
            // Real-time search
            $('#search').on('keyup', function() {
                table.draw();
            });
    
            // Export Excel (manual button)
            $('#exportExcel').on('click', function() {
                table.button('.buttons-excel').trigger();
            });
    
            // Enter key pada search
            $('#search').on('keypress', function(e) {
                if (e.which === 13) {
                    table.draw();
                }
            });
        });
    
        // Helper function untuk extract group dari permission name
        function extractGroup(permissionName) {
            if (!permissionName) return 'other';
            
            var parts = permissionName.split('.');
            if (parts.length > 0) {
                return parts[0];
            }
            return 'other';
        }
    
        // Helper function untuk format tanggal
        function formatDate(dateString) {
            var date = new Date(dateString);
            var day = date.getDate().toString().padStart(2, '0');
            var month = (date.getMonth() + 1).toString().padStart(2, '0');
            var year = date.getFullYear();
            return day + '/' + month + '/' + year;
        }
    
        // SweetAlert confirm delete
        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Permission yang dihapus tidak dapat dikembalikan!",
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
    
        // Refresh table setelah hapus data
        function refreshTable() {
            $('#permissions-table').DataTable().ajax.reload(null, false);
        }
    </script>
@endpush