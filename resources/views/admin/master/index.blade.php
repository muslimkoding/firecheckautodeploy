@extends('admin.template')

@section('title', 'Halaman Master')

@section('breadcrumb')
    <h1 class="mt-4">Master</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item ">Master</li>
    </ol>
@endsection

@section('content')
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-middle">
              <div class="">
              <i class="fa fa-table me-1"></i> List Menu
              </div>
            {{-- <a href="{{ route('zones.create') }}" class="btn btn-sm btn-light border"><div class="fas fa-plus"></div></a> --}}
            </div>
        </div>
        <div class="card-body p-1 bg-light">
          <div class="form-input">
            
            <div class="d-flex justify-content-between align-items-center mb-2">
              
              <div class="me-3" style="flex-grow: 1; min-width: 0;">
                <figure class="mb-0">
                  <blockquote class="blockquote">
                    <p class="text-truncate" style="max-width: 100%;">Zona</p>
                  </blockquote>
                  <figcaption class="blockquote-footer">
                    Membuat zona pemeriksaan peralatan
                  </figcaption>
                </figure>
              </div>
              
              <a href="{{ route('zones.index') }}" class="btn btn-sm btn-light border">Manage</a>
            </div>
        
            <hr class="my-2">
        
            <div class="d-flex justify-content-between align-items-center">
              <div class="me-3" style="flex-grow: 1; min-width: 0;">
                <figure class="mb-0">
                  <blockquote class="blockquote">
                    <p class="text-truncate" style="max-width: 100%;">Gedung</p>
                  </blockquote>
                  <figcaption class="blockquote-footer">
                    Pengaturan Gedung
                  </figcaption>
                </figure>
              </div>
              <a href="{{ route('buildings.index') }}" class="btn btn-sm btn-light border">Manage</a>
            </div>

            <hr class="my-2">
        
            <div class="d-flex justify-content-between align-items-center">
              <div class="me-3" style="flex-grow: 1; min-width: 0;">
                <figure class="mb-0">
                  <blockquote class="blockquote">
                    <p class="text-truncate" style="max-width: 100%;">Lantai</p>
                  </blockquote>
                  <figcaption class="blockquote-footer">
                    Pengaturan Lantai
                  </figcaption>
                </figure>
              </div>
              <a href="{{ route('floors.index') }}" class="btn btn-sm btn-light border">Manage</a>
            </div>

            <hr class="my-2">
        
            <div class="d-flex justify-content-between align-items-center">
              <div class="me-3" style="flex-grow: 1; min-width: 0;">
                <figure class="mb-0">
                  <blockquote class="blockquote">
                    <p class="text-truncate" style="max-width: 100%;">Tipe Apar</p>
                  </blockquote>
                  <figcaption class="blockquote-footer">
                    Pengaturan Tipe Apar
                  </figcaption>
                </figure>
              </div>
              <a href="{{ route('apar-types.index') }}" class="btn btn-sm btn-light border">Manage</a>
            </div>

            <hr class="my-2">
        
            <div class="d-flex justify-content-between align-items-center">
              <div class="me-3" style="flex-grow: 1; min-width: 0;">
                <figure class="mb-0">
                  <blockquote class="blockquote">
                    <p class="text-truncate" style="max-width: 100%;">Tipe Hydrant</p>
                  </blockquote>
                  <figcaption class="blockquote-footer">
                    Pengaturan Tipe Hydrant
                  </figcaption>
                </figure>
              </div>
              <a href="{{ route('hydrant-types.index') }}" class="btn btn-sm btn-light border">Manage</a>
            </div>

            <hr class="my-2">
        
            <div class="d-flex justify-content-between align-items-center">
              <div class="me-3" style="flex-grow: 1; min-width: 0;">
                <figure class="mb-0">
                  <blockquote class="blockquote">
                    <p class="text-truncate" style="max-width: 100%;">Kondisi Peralatan</p>
                  </blockquote>
                  <figcaption class="blockquote-footer">
                    Pengaturan Kondisi Peralatan
                  </figcaption>
                </figure>
              </div>
              <a href="{{ route('extinguisher-conditions.index') }}" class="btn btn-sm btn-light border">Manage</a>
            </div>

            <hr class="my-2">
        
            <div class="d-flex justify-content-between align-items-center">
              <div class="me-3" style="flex-grow: 1; min-width: 0;">
                <figure class="mb-0">
                  <blockquote class="blockquote">
                    <p class="text-truncate" style="max-width: 100%;">Group</p>
                  </blockquote>
                  <figcaption class="blockquote-footer">
                    Pengaturan Group
                  </figcaption>
                </figure>
              </div>
              <a href="{{ route('groups.index') }}" class="btn btn-sm btn-light border">Manage</a>
            </div>

            <hr class="my-2">
        
            <div class="d-flex justify-content-between align-items-center">
              <div class="me-3" style="flex-grow: 1; min-width: 0;">
                <figure class="mb-0">
                  <blockquote class="blockquote">
                    <p class="text-truncate" style="max-width: 100%;">Merek</p>
                  </blockquote>
                  <figcaption class="blockquote-footer">
                    Pengaturan Merek
                  </figcaption>
                </figure>
              </div>
              <a href="{{ route('brands.index') }}" class="btn btn-sm btn-light border">Manage</a>
            </div>

            <hr class="my-2">
        
            <div class="d-flex justify-content-between align-items-center">
              <div class="me-3" style="flex-grow: 1; min-width: 0;">
                <figure class="mb-0">
                  <blockquote class="blockquote">
                    <p class="text-truncate" style="max-width: 100%;">Status</p>
                  </blockquote>
                  <figcaption class="blockquote-footer">
                    Pengaturan Status
                  </figcaption>
                </figure>
              </div>
              <a href="{{ route('statuses.index') }}" class="btn btn-sm btn-light border">Manage</a>
            </div>

            <hr class="my-2">
        
            <div class="d-flex justify-content-between align-items-center">
              <div class="me-3" style="flex-grow: 1; min-width: 0;">
                <figure class="mb-0">
                  <blockquote class="blockquote">
                    <p class="text-truncate" style="max-width: 100%;">Status Pegawai</p>
                  </blockquote>
                  <figcaption class="blockquote-footer">
                    Pengaturan Status Pegawai
                  </figcaption>
                </figure>
              </div>
              <a href="{{ route('employee-types.index') }}" class="btn btn-sm btn-light border">Manage</a>
            </div>

            <hr class="my-2">
        
            <div class="d-flex justify-content-between align-items-center">
              <div class="me-3" style="flex-grow: 1; min-width: 0;">
                <figure class="mb-0">
                  <blockquote class="blockquote">
                    <p class="text-truncate" style="max-width: 100%;">Jabatan</p>
                  </blockquote>
                  <figcaption class="blockquote-footer">
                    Pengaturan Jabatan
                  </figcaption>
                </figure>
              </div>
              <a href="{{ route('positions.index') }}" class="btn btn-sm btn-light border">Manage</a>
            </div>

            <hr class="my-2">
        
            <div class="d-flex justify-content-between align-items-center">
              <div class="me-3" style="flex-grow: 1; min-width: 0;">
                <figure class="mb-0">
                  <blockquote class="blockquote">
                    <p class="text-truncate" style="max-width: 100%;">Kompetensi</p>
                  </blockquote>
                  <figcaption class="blockquote-footer">
                    Pengaturan Kompetensi
                  </figcaption>
                </figure>
              </div>
              <a href="{{ route('competencies.index') }}" class="btn btn-sm btn-light border">Manage</a>
            </div>

            <hr class="my-2">
        
            <div class="d-flex justify-content-between align-items-center">
              <div class="me-3" style="flex-grow: 1; min-width: 0;">
                <figure class="mb-0">
                  <blockquote class="blockquote">
                    <p class="text-truncate" style="max-width: 100%;">Tekanan APAR</p>
                  </blockquote>
                  <figcaption class="blockquote-footer">
                    Jenis - jenis kondisi tekanan APAR
                  </figcaption>
                </figure>
              </div>
              <a href="{{ route('apar-pressures.index') }}" class="btn btn-sm btn-light border">Manage</a>
            </div>

            <hr class="my-2">
        
            <div class="d-flex justify-content-between align-items-center">
              <div class="me-3" style="flex-grow: 1; min-width: 0;">
                <figure class="mb-0">
                  <blockquote class="blockquote">
                    <p class="text-truncate" style="max-width: 100%;">Tabung APAR</p>
                  </blockquote>
                  <figcaption class="blockquote-footer">
                    Jenis - jenis kondisi tabung APAR
                  </figcaption>
                </figure>
              </div>
              <a href="{{ route('apar-cylinders.index') }}" class="btn btn-sm btn-light border">Manage</a>
            </div>

            <hr class="my-2">
        
            <div class="d-flex justify-content-between align-items-center">
              <div class="me-3" style="flex-grow: 1; min-width: 0;">
                <figure class="mb-0">
                  <blockquote class="blockquote">
                    <p class="text-truncate" style="max-width: 100%;">Sil Pin APAR</p>
                  </blockquote>
                  <figcaption class="blockquote-footer">
                    Jenis - jenis kondisi Sil Pin APAR
                  </figcaption>
                </figure>
              </div>
              <a href="{{ route('apar-pin-seals.index') }}" class="btn btn-sm btn-light border">Manage</a>
            </div>

            <hr class="my-2">
        
            <div class="d-flex justify-content-between align-items-center">
              <div class="me-3" style="flex-grow: 1; min-width: 0;">
                <figure class="mb-0">
                  <blockquote class="blockquote">
                    <p class="text-truncate" style="max-width: 100%;">Selang APAR</p>
                  </blockquote>
                  <figcaption class="blockquote-footer">
                    Jenis - jenis kondisi selang APAR
                  </figcaption>
                </figure>
              </div>
              <a href="{{ route('apar-hoses.index') }}" class="btn btn-sm btn-light border">Manage</a>
            </div>

            <hr class="my-2">
        
            <div class="d-flex justify-content-between align-items-center">
              <div class="me-3" style="flex-grow: 1; min-width: 0;">
                <figure class="mb-0">
                  <blockquote class="blockquote">
                    <p class="text-truncate" style="max-width: 100%;">Handle APAR</p>
                  </blockquote>
                  <figcaption class="blockquote-footer">
                    Jenis - jenis kondisi handle APAR
                  </figcaption>
                </figure>
              </div>
              <a href="{{ route('apar-handles.index') }}" class="btn btn-sm btn-light border">Manage</a>
            </div>

            <hr class="my-2">
        
            <div class="d-flex justify-content-between align-items-center">
              <div class="me-3" style="flex-grow: 1; min-width: 0;">
                <figure class="mb-0">
                  <blockquote class="blockquote">
                    <p class="text-truncate" style="max-width: 100%;">Pintu Hydrant</p>
                  </blockquote>
                  <figcaption class="blockquote-footer">
                    Jenis - jenis kondisi Pintu Hydrant
                  </figcaption>
                </figure>
              </div>
              <a href="{{ route('hydrant-doors.index') }}" class="btn btn-sm btn-light border">Manage</a>
            </div>

            <hr class="my-2">
        
            <div class="d-flex justify-content-between align-items-center">
              <div class="me-3" style="flex-grow: 1; min-width: 0;">
                <figure class="mb-0">
                  <blockquote class="blockquote">
                    <p class="text-truncate" style="max-width: 100%;">Kopling Hydrant</p>
                  </blockquote>
                  <figcaption class="blockquote-footer">
                    Jenis - jenis kondisi Kopling Hydrant
                  </figcaption>
                </figure>
              </div>
              <a href="{{ route('hydrant-couplings.index') }}" class="btn btn-sm btn-light border">Manage</a>
            </div>

            <hr class="my-2">
        
            <div class="d-flex justify-content-between align-items-center">
              <div class="me-3" style="flex-grow: 1; min-width: 0;">
                <figure class="mb-0">
                  <blockquote class="blockquote">
                    <p class="text-truncate" style="max-width: 100%;">Main Valve Hydrant</p>
                  </blockquote>
                  <figcaption class="blockquote-footer">
                    Jenis - jenis kondisi Main Valve Hydrant
                  </figcaption>
                </figure>
              </div>
              <a href="{{ route('hydrant-main-valve.index') }}" class="btn btn-sm btn-light border">Manage</a>
            </div>

            <hr class="my-2">
        
            <div class="d-flex justify-content-between align-items-center">
              <div class="me-3" style="flex-grow: 1; min-width: 0;">
                <figure class="mb-0">
                  <blockquote class="blockquote">
                    <p class="text-truncate" style="max-width: 100%;">Selang Hydrant</p>
                  </blockquote>
                  <figcaption class="blockquote-footer">
                    Jenis - jenis kondisi Selang Hydrant
                  </figcaption>
                </figure>
              </div>
              <a href="{{ route('hydrant-hoses.index') }}" class="btn btn-sm btn-light border">Manage</a>
            </div>

            <hr class="my-2">
        
            <div class="d-flex justify-content-between align-items-center">
              <div class="me-3" style="flex-grow: 1; min-width: 0;">
                <figure class="mb-0">
                  <blockquote class="blockquote">
                    <p class="text-truncate" style="max-width: 100%;">Nozzle Hydrant</p>
                  </blockquote>
                  <figcaption class="blockquote-footer">
                    Jenis - jenis kondisi Nozzle Hydrant
                  </figcaption>
                </figure>
              </div>
              <a href="{{ route('hydrant-nozzles.index') }}" class="btn btn-sm btn-light border">Manage</a>
            </div>

            <hr class="my-2">
        
            <div class="d-flex justify-content-between align-items-center">
              <div class="me-3" style="flex-grow: 1; min-width: 0;">
                <figure class="mb-0">
                  <blockquote class="blockquote">
                    <p class="text-truncate" style="max-width: 100%;">Marka Safety Hydrant</p>
                  </blockquote>
                  <figcaption class="blockquote-footer">
                    Jenis - jenis kondisi Marka Safety Hydrant
                  </figcaption>
                </figure>
              </div>
              <a href="{{ route('hydrant-safety-markings.index') }}" class="btn btn-sm btn-light border">Manage</a>
            </div>

            <hr class="my-2">
        
            <div class="d-flex justify-content-between align-items-center">
              <div class="me-3" style="flex-grow: 1; min-width: 0;">
                <figure class="mb-0">
                  <blockquote class="blockquote">
                    <p class="text-truncate" style="max-width: 100%;">Panduan Hydrant</p>
                  </blockquote>
                  <figcaption class="blockquote-footer">
                    Jenis - jenis kondisi Panduan Hydrant
                  </figcaption>
                </figure>
              </div>
              <a href="{{ route('hydrant-guides.index') }}" class="btn btn-sm btn-light border">Manage</a>
            </div>
        
          </div>
        </div>
    </div>
@endsection
