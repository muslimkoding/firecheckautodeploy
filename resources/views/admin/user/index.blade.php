@extends('admin.template')

@section('title', 'Manajemen User')

@section('breadcrumb')
    <h1 class="mt-4">Manajemen User</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item text-dark">Manajemen User</li>
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
                </button>
                <button type="button" class="btn btn-info btn-sm" id="printData">
                    <i class="fas fa-print"></i> Print
                </button> --}}
                <a href="{{ route('user.create') }}" class="btn btn-sm btn-light bg-white border">
                    <div class="fas fa-plus"></div>
                </a>
            </div>
        </div>
    </div>

    <div class="card-body p-1 bg-light">

        <!-- ====================== FILTER ====================== -->
        <div class="p-2">
            <div class="row mb-3">

                <div class="col-md-3">
                    <label class="form-label">Tipe Pegawai</label>
                    <select class="form-select form-select-sm" id="employee_type_filter">
                        <option value="">Semua Tipe</option>
                        @foreach($employeeTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Group</label>
                    <select class="form-select form-select-sm" id="group_filter">
                        <option value="">Semua Group</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Posisi</label>
                    <select class="form-select form-select-sm" id="position_filter">
                        <option value="">Semua Posisi</option>
                        @foreach($positions as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Kompetensi</label>
                    <select class="form-select form-select-sm" id="competency_filter">
                        <option value="">Semua Kompetensi</option>
                        @foreach($competencies as $comp)
                            <option value="{{ $comp->id }}">{{ $comp->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-3">

                {{-- <div class="col-md-3">
                    <label class="form-label">Status Email</label>
                    <select id="email_verified_filter" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="verified">Terverifikasi</option>
                        <option value="unverified">Belum Verifikasi</option>
                    </select>
                </div> --}}

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
                  <label class="form-label">Cari Karyawan</label>
                  <input type="text" class="form-control" id="search" placeholder="Cari Karyawan...">
              </div>
          </div>
        </div>

        <!-- ====================== TABLE ====================== -->
        <div class="table-responsive">
            <div class="form-input">
                <table class="table border w-100 bg-white" id="datatables">
                    <thead>
                        <tr class="align-middle">
                            <th class="text-center" style="width: 4rem;">#</th>
                            <th class="text-center" style="width:60px">Avatar</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>NIP</th>
                            {{-- <th>Tgl lahir</th> --}}
                            <th>Tipe Pegawai</th>
                            <th>Group</th>
                            {{-- <th>Posisi</th> --}}
                            {{-- <th>Kompetensi</th> --}}
                            <th>Roles</th>
                            {{-- <th>Status Email</th> --}}
                            <th class="text-center" style="width: 12rem;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- auto-load DataTables -->
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
@endpush

@push('scripts')
<script src="{{ asset('assets/sweetalert.js') }}"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<!-- Buttons -->
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<!-- PDF Export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

<script>
$(document).ready(function() {

    var table = $('#datatables').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: "{{ route('user.index') }}",
            data: function(d) {
                d.search = $('#search').val();
                d.employee_type_id = $('#employee_type_filter').val();
                d.group_id = $('#group_filter').val();
                d.position_id = $('#position_filter').val();
                d.competency_id = $('#competency_filter').val();
                d.email_verified = $('#email_verified_filter').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', className: 'text-center', orderable: false, searchable: false },
            { data: 'avatar_display', className: 'text-center', orderable: false, searchable: false },
            { data: 'name' },
            { data: 'email' },
            { data: 'nip' },
            // { data: 'formatted_date_birth' },
            { data: 'employee_type_name' },
            { data: 'group_name' },
            // { data: 'position_name' },
            // { data: 'competency_name' },
            { data: 'roles_display', className: 'text-center', orderable: false, searchable: false },
            // { data: 'email_status', className: 'text-center', orderable: false },
            { data: 'action', className: 'text-center', orderable: false, searchable: false },
        ],
        order: [[2, 'asc']],
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                     '<"row"<"col-sm-12"tr>>' +
                     '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>' +
                     '<"row"<"col-sm-12"B>>',
        buttons: [
            {
                extend: 'excel',
                className: 'btn btn-success btn-sm',
                text: '<i class="fas fa-file-excel"></i> Excel',
                exportOptions: { columns: [0,2,3,4,5,6,7,8,9,10,11] }
            },
            {
                extend: 'print',
                className: 'btn btn-info btn-sm',
                text: '<i class="fas fa-print"></i> Print',
                exportOptions: { columns: [0,2,3,4,5,6,7,8,9,10,11] }
            },
        ]
    });

    $('#applyFilter').click(() => table.ajax.reload());
    $('#resetFilter').click(() => {
        $('#employee_type_filter, #group_filter, #position_filter, #competency_filter, #email_verified_filter, #search').val('');
        table.ajax.reload();
    });

    // Real-time search
    $('#search').on('keyup', function() {
                table.draw();
            });

});

function confirmDelete(id) {
      Swal.fire({
        title: 'Apakah Anda Yakin?',
        text: "Data user yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33'
      }).then((result) => {
        if (result.isConfirmed) {
          document.getElementById('delete-form-' + id).submit();
        }
      });
    }
</script>
@endpush


{{-- @extends('admin.template')

@section('title', 'Manajemen User')

@section('breadcrumb')
  <h1 class="mt-4">Manajemen User</h1>
  <ol class="breadcrumb mb-4">
    <li class="breadcrumb-item">List User</li>
  </ol>
@endsection

@section('content')
  <div class="row">
    <div class="col-md-12">
      <div class="card mb-4">
        <div class="card-header">
          <div class="d-flex justify-content-between align-items-center">
            <div class="">List Data User</div>
            <a href="{{ route('user.create') }}" class="btn btn-sm bg-white btn-custom-hover">
              <i class="fa-solid fa-plus"></i> Tambah User
            </a>
          </div>
        </div>
        <div class="card-body p-1 bg-light">
          <!-- Filters -->
          <div class="p-2">
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="employee_type_filter" class="form-label small">Filter Tipe Pegawai</label>
                    <select id="employee_type_filter" class="form-select form-select-sm">
                        <option value="">Semua Tipe</option>
                        @foreach($employeeTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="group_filter" class="form-label small">Filter Group</label>
                    <select id="group_filter" class="form-select form-select-sm">
                        <option value="">Semua Group</option>
                        @foreach($groups as $group)
                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="position_filter" class="form-label small">Filter Posisi</label>
                    <select id="position_filter" class="form-select form-select-sm">
                        <option value="">Semua Posisi</option>
                        @foreach($positions as $position)
                            <option value="{{ $position->id }}">{{ $position->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="competency_filter" class="form-label small">Filter Kompetensi</label>
                    <select id="competency_filter" class="form-select form-select-sm">
                        <option value="">Semua Kompetensi</option>
                        @foreach($competencies as $competency)
                            <option value="{{ $competency->id }}">{{ $competency->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="email_verified_filter" class="form-label small">Filter Status Email</label>
                    <select id="email_verified_filter" class="form-select form-select-sm">
                        <option value="">Semua Status</option>
                        <option value="verified">Terverifikasi</option>
                        <option value="unverified">Belum Verifikasi</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button id="reset_filter" class="btn btn-sm btn-outline-secondary">
                        <i class="fa-solid fa-refresh"></i> Reset Filter
                    </button>
                </div>
            </div>
          </div>

          <div class="table-responsive">
            <table class="table border mb-0 align-middle bg-white" id="user-table" style="width: 100% !important;">
              <thead>
                <tr>
                  <th class="text-center" style="width: 4rem">#</th>
                  <th class="text-center" style="width: 60px">Avatar</th>
                  <th>Nama</th>
                  <th>Email</th>
                  <th>NIP</th>
                  <th>Tgl. Lahir</th>
                  <th>Tipe Pegawai</th>
                  <th>Group</th>
                  <th>Posisi</th>
                  <th>Kompetensi</th>
                  <th>Status Email</th>
                  <th style="width: 12rem">Aksi</th>
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
@endsection

@push('styles')
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
  <style>
    .table img {
        border: 2px solid #dee2e6;
    }
    .bg-secondary {
        background-color: #6c757d !important;
    }
  </style>
@endpush

@push('scripts')
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
  <script src="{{ asset('assets/sweetalert.js') }}"></script>

  <script>
    $(document).ready(function() {
      // Inisialisasi DataTable
      var table = $('#user-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
          url: "{{ route('user.index') }}",
          data: function (d) {
            d.employee_type_id = $('#employee_type_filter').val();
            d.group_id = $('#group_filter').val();
            d.position_id = $('#position_filter').val();
            d.competency_id = $('#competency_filter').val();
            d.email_verified = $('#email_verified_filter').val();
          },
          error: function(xhr, error, thrown) {
            console.error('DataTables AJAX Error:', error);
            console.error('Response:', xhr.responseText);
            alert('Terjadi kesalahan saat memuat data. Silakan cek console untuk detail.');
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
            data: 'avatar_display',
            name: 'avatar_display',
            orderable: false,
            searchable: false,
            className: 'text-center'
          },
          {
            data: 'name',
            name: 'name'
          },
          {
            data: 'email',
            name: 'email'
          },
          {
            data: 'nip',
            name: 'nip'
          },
          {
            data: 'formatted_date_birth',
            name: 'date_birth'
          },
          {
            data: 'employee_type_name',
            name: 'employeeType.name'
          },
          {
            data: 'group_name',
            name: 'group.name'
          },
          {
            data: 'position_name',
            name: 'position.name'
          },
          {
            data: 'competency_name',
            name: 'competency.name'
          },
          {
            data: 'email_status',
            name: 'email_verified_at',
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
        order: [[2, 'asc']], // Default order by name
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
        dom: '<"row"<"col-md-6"B><"col-md-6"f>>rt<"row"<"col-md-6"l><"col-md-6"p>>',
        buttons: [
          {
            extend: 'excel',
            className: 'btn btn-sm btn-success',
            text: '<i class="fa-solid fa-file-excel"></i> Excel',
            exportOptions: {
              columns: [0, 2, 3, 4, 5, 6, 7, 8, 9, 10] // Sesuaikan index kolom
            }
          },
          {
            extend: 'pdf',
            className: 'btn btn-sm btn-danger',
            text: '<i class="fa-solid fa-file-pdf"></i> PDF',
            exportOptions: {
              columns: [0, 2, 3, 4, 5, 6, 7, 8, 9, 10]
            }
          },
          {
            extend: 'print',
            className: 'btn btn-sm btn-info',
            text: '<i class="fa-solid fa-print"></i> Print',
            exportOptions: {
              columns: [0, 2, 3, 4, 5, 6, 7, 8, 9, 10]
            }
          }
        ]
      });

      // Filter events
      $('#employee_type_filter, #group_filter, #position_filter, #competency_filter, #email_verified_filter').change(function() {
        table.ajax.reload();
      });

      // Reset filter
      $('#reset_filter').click(function() {
        $('#employee_type_filter, #group_filter, #position_filter, #competency_filter, #email_verified_filter').val('');
        table.ajax.reload();
      });

      // Search delay
      var searchTimeout;
      $('#user-table_filter input').on('keyup', function() {
        var searchValue = $(this).val();
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
          table.search(searchValue).draw();
        }, 500);
      });
    });

    function confirmDelete(id) {
      Swal.fire({
        title: 'Apakah Anda Yakin?',
        text: "Data user yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33'
      }).then((result) => {
        if (result.isConfirmed) {
          document.getElementById('delete-form-' + id).submit();
        }
      });
    }
  </script>
@endpush --}}