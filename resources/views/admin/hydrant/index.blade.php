@extends('admin.template')

@section('title', 'Halaman Data Hydrant')

@section('breadcrumb')
    <h1 class="mt-4">Data Hydrant</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item "><a href="{{ route('hydrant.index') }}" class="text-decoration-none text-dark">Mapping</a></li>
        <li class="breadcrumb-item ">Data Hydrant</li>
    </ol>
@endsection

@section('content')
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-middle">
                <div>
                    {{-- <a href="{{ route('master.index') }}" class="text-decoration-none text-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a> --}}
                <i class="fa-solid fa-table-list"></i> Data Hydrant

                </div>
                <div class="d-flex gap-2">
                    {{-- <button type="button" class="btn btn-success btn-sm" id="exportExcel">
                        <i class="fas fa-file-excel"></i> Export
                    </button>
                    <button type="button" class="btn btn-info btn-sm" id="printData">
                        <i class="fas fa-print"></i> Print
                    </button> --}}
                    <a href="{{ route('hydrant.create') }}" class="btn btn-sm btn-light bg-white border">
                        <div class="fas fa-plus"></div>
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body p-1 bg-light">
            <!-- Filter Section -->
            <div class="p-2">
                <div class="row mb-4">
                    <div class="col-md-2">
                        <label class="form-label">Zona</label>
                        <select class="form-select form-select-sm" id="zone_id">
                            <option value="">Semua Zona</option>
                            @foreach($zones as $zone)
                                <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Gedung</label>
                        <select class="form-select form-select-sm" id="building_id">
                            <option value="">Semua Gedung</option>
                            @foreach($buildings as $building)
                                <option value="{{ $building->id }}">{{ $building->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Lantai</label>
                        <select class="form-select form-select-sm" id="floor_id">
                            <option value="">Semua Lantai</option>
                            @foreach($floors as $floor)
                                <option value="{{ $floor->id }}">{{ $floor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Merek</label>
                        <select class="form-select form-select-sm" id="brand_id">
                            <option value="">Semua Merek</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Tipe Hydrant</label>
                        <select class="form-select form-select-sm" id="hydrant_type_id">
                            <option value="">Semua Tipe</option>
                            @foreach($hydrantType as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Kondisi</label>
                        <select class="form-select form-select-sm" id="condition_id">
                            <option value="">Semua Kondisi</option>
                            @foreach($conditions as $condition)
                                <option value="{{ $condition->id }}">{{ $condition->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
    
                <div class="row mb-4">
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select class="form-select form-select-sm" id="status">
                            <option value="">Semua Status</option>
                            <option value="active">Aktif</option>
                            <option value="inactive">Non-Aktif</option>
                        </select>
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <button type="button" class="btn btn-sm btn-primary me-2" id="applyFilter">
                            <i class="fas fa-filter"></i> Terapkan Filter
                        </button>
                        <button type="button" class="btn btn-sm btn-secondary" id="resetFilter">
                            <i class="fas fa-refresh"></i> Reset
                        </button>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-12">
                        <label class="form-label">Cari Hydrant</label>
                        <input type="text" class="form-control form-select-sm" id="search" placeholder="Cari Hydrant...">
                    </div>
                </div>
            </div>

            <div class="table-responsive" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                <div class="form-input">
                    <table class="table border w-100 bg-white" id="datatables">
                        <thead>
                            <tr class="align-middle">
                                <th class="text-center" style="width: 4rem;">#</th>
                                <th>Nomor Hydrant</th>
                                <th>Barcode</th>
                                <th>Lokasi</th>
                                <th>Zona</th>
                                <th>Gedung</th>
                                <th>Lantai</th>
                                <th>Merek</th>
                                <th>Tipe</th>
                                <th>Kondisi</th>
                                <th>Status</th>
                                <th class="text-center" style="width: 12rem;">Aksi</th>
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
            
            /* Horizontal scroll indicator */
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
            
            #datatables {
                width: 100% !important;
            }
        }

        /* Badge styling */
        .badge {
            font-size: 0.75em;
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
        var table = $('#datatables').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('hydrant.index') }}",
                type: 'GET',
                data: function(d) {
                    d.search = $('#search').val();
                    d.zone_id = $('#zone_id').val();
                    d.building_id = $('#building_id').val();
                    d.floor_id = $('#floor_id').val();
                    d.brand_id = $('#brand_id').val();
                    d.hydrant_type_id = $('#hydrant_type_id').val();
                    d.condition_id = $('#condition_id').val();
                    d.status = $('#status').val();
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
                    data: 'number_hydrant',
                    name: 'number_hydrant'
                },
                {
                    data: 'qrcode_display',
                    name: 'qrcode_display',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'location',
                    name: 'location'
                },
                {
                    data: 'zone.name',
                    name: 'zone.name'
                },
                {
                    data: 'building.name',
                    name: 'building.name'
                },
                {
                    data: 'floor.name',
                    name: 'floor.name'
                },
                {
                    data: 'brand.name',
                    name: 'brand.name'
                },
                {
                    data: 'hydrant_type.name',
                    name: 'hydrantType.name'
                },
                {
                    data: 'extinguisher_condition.name',
                    name: 'extinguisherCondition.name'
                },
                {
                    data: 'status_badge',
                    name: 'is_active',
                    orderable: false,
                    searchable: false
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
            order: [[1, 'asc']], // Default order by nomor Hydrant
            pageLength: 10,
            lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "Semua"]],
            drawCallback: function(settings) {
                // Update tooltips setelah table redraw
                $('[data-bs-toggle="tooltip"]').tooltip();
            },
            // Custom DOM layout dengan buttons di atas
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
                        columns: [0, 1, 3, 4, 5, 6, 7, 8, 9, 10]
                    }
                },
                // {
                //     extend: 'pdf',
                //     className: 'btn btn-danger btn-sm',
                //     text: '<i class="fas fa-file-pdf"></i> PDF',
                //     orientation: 'landscape', // Set landscape untuk PDF
                //     pageSize: 'A4', // Set ukuran kertas
                //     exportOptions: {
                //         columns: [0, 1, 3, 4, 5, 6, 7, 8, 9, 10],
                //         stripHtml: true
                //     },
                //     customize: function (doc) {
                //         // Custom styling untuk PDF landscape
                //         doc.defaultStyle.fontSize = 8;
                //         doc.styles.tableHeader.fontSize = 9;
                //         doc.styles.tableHeader.alignment = 'center';
                //         doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                        
                //         // Set margin
                //         doc.pageMargins = [20, 40, 20, 40];
                        
                //         // Set layout
                //         doc.layout = {
                //             hLineWidth: function(i, node) {
                //                 return 0.5;
                //             },
                //             vLineWidth: function(i, node) {
                //                 return 0.5;
                //             },
                //             hLineColor: function(i, node) {
                //                 return '#aaa';
                //             },
                //             vLineColor: function(i, node) {
                //                 return '#aaa';
                //             },
                //             paddingLeft: function(i, node) {
                //                 return 4;
                //             },
                //             paddingRight: function(i, node) {
                //                 return 4;
                //             },
                //             paddingTop: function(i, node) {
                //                 return 2;
                //             },
                //             paddingBottom: function(i, node) {
                //                 return 2;
                //             }
                //         };
                //     }
                // },
                {
                    extend: 'print',
                    className: 'btn btn-info btn-sm',
                    text: '<i class="fas fa-print"></i> Print',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    exportOptions: {
                        columns: [0, 1, 3, 4, 5, 6, 7, 8, 9, 10],
                        stripHtml: false
                    },
                    customize: function (win) {
                        // Custom styling untuk print
                        $(win.document.body).find('table').addClass('display').css('font-size', '10px');
                        $(win.document.body).find('h1').css('text-align','center');
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
            $('#zone_id').val('');
            $('#building_id').val('');
            $('#floor_id').val('');
            $('#brand_id').val('');
            $('#hydrant_type_id').val('');
            $('#condition_id').val('');
            $('#status').val('');
            $('#expired_status').val('');
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

        // Print Data (manual button)
        $('#printData').on('click', function() {
            table.button('.buttons-print').trigger();
        });

        // Enter key pada filter
        $('.form-select').on('keypress', function(e) {
            if (e.which === 13) {
                table.draw();
            }
        });
    });

    // sweetalert confirm delete
    function confirmDelete(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data Hydrant yang dihapus tidak dapat dikembalikan!",
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
        $('#datatables').DataTable().ajax.reload(null, false);
    }
</script>
@endpush