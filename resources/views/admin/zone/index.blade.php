@extends('admin.template')

@section('title', 'Halaman Zona')

@section('breadcrumb')
    <h1 class="mt-4">Zona</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item "><a href="{{ route('master.index') }}" class="text-decoration-none text-secondary">Master</a></li>
        <li class="breadcrumb-item "><a href="{{ route('zones.index') }}" class="text-decoration-none text-dark">Zona</a></li>
    </ol>
@endsection

@section('content')
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-middle">
                <div>
                    <a href="{{ route('master.index') }}" class="text-decoration-none text-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
                <a href="{{ route('zones.create') }}" class="btn btn-sm btn-light bg-white border">
                    <div class="fas fa-plus"></div>
                </a>
            </div>
        </div>
        <div class="card-body p-1 bg-light">
            <div class="table-responsive" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
                <div class="form-input">
                  <table class="table border w-100 bg-white" id="datatable">
                    <thead>
                        <tr class="align-middle">
                            <th class="text-center" style="width: 4rem;">#</th>
                            <th>Nama Zona</th>
                            <th>Slug</th>
                            <th>Deskripsi</th>
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
                    url: "{{ route('zones.index') }}",
                    type: 'GET'
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
                        name: 'name'
                    },
                    {
                        data: 'slug',
                        name: 'slug'
                    },
                    {
                        data: 'description',
                        name: 'description'
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
                // order: [[1, 'asc']] // Default order by name
            });
        });

        // sweetalert confirm delete
        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
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