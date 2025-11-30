@extends('admin.template')

@section('title', 'Edit APAR')

@section('breadcrumb')
  <h1 class="mt-4">APAR</h1>
  <ol class="breadcrumb mb-4">
    <li class="breadcrumb-item"><a href="{{ route('apar.index') }}" class="text-decoration-none text-secondary">List</a></li>
    <li class="breadcrumb-item">Edit</li>
  </ol>
@endsection

@section('content')
  <div class="row">
    <div class="col-md-12">
      <div class="card mb-4">
        <div class="card-header">
          <div class="d-flex justify-content-between">
            <a href="{{ route('apar.index') }}" class="text-decoration-none text-dark"><i class="fa-solid fa-arrow-left"></i> Back</a>
          </div>
        </div>
        <div class="card-body p-1 bg-light">
          <div class="form-input">
            <form action="{{ route('apar.update', $apar->id) }}" method="POST">
              @csrf
              @method('PUT')
              
              <!-- Number APAR -->
              <div class="mb-3">
                  <label for="number_apar" class="form-label">Nomor APAR *</label>
                  <input type="text" class="form-control @error('number_apar') is-invalid @enderror" 
                        id="number_apar" name="number_apar" 
                        value="{{ old('number_apar', $apar->number_apar) }}" 
                        style="text-transform: uppercase;"
                        required>
                  @error('number_apar')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
              </div>
              
              <!-- Location -->
              <div class="mb-3">
                  <label for="location" class="form-label">Lokasi *</label>
                  <input type="text" class="form-control @error('location') is-invalid @enderror" 
                        id="location" name="location" 
                        value="{{ old('location', $apar->location) }}" required>
                  @error('location')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
              </div>
              
              <!-- Weight -->
              <div class="mb-3">
                  <label for="weight_of_extinguiser" class="form-label">Berat (kg) *</label>
                  <input type="number" step="0.01" class="form-control @error('weight_of_extinguiser') is-invalid @enderror" 
                        id="weight_of_extinguiser" name="weight_of_extinguiser" 
                        value="{{ old('weight_of_extinguiser', $apar->weight_of_extinguiser) }}" required>
                  @error('weight_of_extinguiser')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
              </div>
              
              <!-- Expired Date -->
              <div class="mb-3">
                  <label for="expired_date" class="form-label">Tanggal Expired *</label>
                  <input type="date" class="form-control @error('expired_date') is-invalid @enderror" 
                        id="expired_date" name="expired_date" 
                        value="{{ old('expired_date', $apar->expired_date->format('Y-m-d')) }}" required>
                  @error('expired_date')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
              </div>
              
              <!-- Status -->
              <div class="mb-3">
                  <label class="form-label">Status</label>
                  <div>
                      <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="is_active" 
                                id="is_active_true" value="1" 
                                {{ old('is_active', $apar->is_active) == 1 ? 'checked' : '' }}>
                          <label class="form-check-label" for="is_active_true">Aktif</label>
                      </div>
                      <div class="form-check form-check-inline">
                          <input class="form-check-input" type="radio" name="is_active" 
                                id="is_active_false" value="0" 
                                {{ old('is_active', $apar->is_active) == 0 ? 'checked' : '' }}>
                          <label class="form-check-label" for="is_active_false">Tidak Aktif</label>
                      </div>
                  </div>
                  @error('is_active')
                      <div class="text-danger small">{{ $message }}</div>
                  @enderror
              </div>
              
              <!-- Foreign keys (select dropdowns) -->
              <div class="mb-3">
                  <label for="zone_id" class="form-label">Zona *</label>
                  <select class="form-select @error('zone_id') is-invalid @enderror" 
                          id="zone_id" name="zone_id" required>
                      <option value="">Pilih Zona</option>
                      @foreach($zones as $zone)
                          <option value="{{ $zone->id }}" 
                              {{ old('zone_id', $apar->zone_id) == $zone->id ? 'selected' : '' }}>
                              {{ $zone->name }}
                          </option>
                      @endforeach
                  </select>
                  @error('zone_id')
                      <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
              </div>

              <div class="mb-3">
                <label for="building_id" class="form-label">Gedung *</label>
                <select name="building_id" id="building_id" class="form-select @error('building_id') is-invalid @enderror" required>
                  <option value="">Pilih Gedung</option>
                  @foreach ($buildings as $building)
                    <option value="{{ $building->id }}" 
                      {{ old('building_id', $apar->building_id) == $building->id ? 'selected' : '' }}>
                      {{ $building->name }}
                    </option>
                  @endforeach
                </select>
                @error('building_id')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-3">
                <label for="floor_id" class="form-label">Lantai *</label>
                <select name="floor_id" id="floor_id" class="form-select @error('floor_id') is-invalid @enderror" required>
                  <option value="">Pilih Lantai</option>
                  @foreach ($floors as $floor)
                    <option value="{{ $floor->id }}" 
                      {{ old('floor_id', $apar->floor_id) == $floor->id ? 'selected' : '' }}>
                      {{ $floor->name }}
                    </option>
                  @endforeach
                </select>
                @error('floor_id')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-3">
                <label for="brand_id" class="form-label">Merek *</label>
                <select name="brand_id" id="brand_id" class="form-select @error('brand_id') is-invalid @enderror" required>
                  <option value="">Pilih Merek</option>
                  @foreach ($brands as $brand)
                    <option value="{{ $brand->id }}" 
                      {{ old('brand_id', $apar->brand_id) == $brand->id ? 'selected' : '' }}>
                      {{ $brand->name }}
                    </option>
                  @endforeach
                </select>
                @error('brand_id')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-3">
                <label for="apar_type_id" class="form-label">Tipe APAR *</label>
                <select name="apar_type_id" id="apar_type_id" class="form-select @error('apar_type_id') is-invalid @enderror" required>
                  <option value="">Pilih Tipe</option>
                  @foreach ($aparTypes as $aparType)
                    <option value="{{ $aparType->id }}" 
                      {{ old('apar_type_id', $apar->apar_type_id) == $aparType->id ? 'selected' : '' }}>
                      {{ $aparType->name }}
                    </option>
                  @endforeach
                </select>
                @error('apar_type_id')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-3">
                <label for="extinguisher_condition_id" class="form-label">Kondisi APAR *</label>
                <select name="extinguisher_condition_id" id="extinguisher_condition_id" class="form-select @error('extinguisher_condition_id') is-invalid @enderror" required>
                  <option value="">Pilih Kondisi</option>
                  @foreach ($extinguisherConditions as $extinguiserCondition)
                    <option value="{{ $extinguiserCondition->id }}" 
                      {{ old('extinguisher_condition_id', $apar->extinguisher_condition_id) == $extinguiserCondition->id ? 'selected' : '' }}>
                      {{ $extinguiserCondition->name }}
                    </option>
                  @endforeach
                </select>
                @error('extinguisher_condition_id')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              
              <!-- Tombol Submit -->
              <div class="text-end">
                <button type="reset" class="btn btn-sm btn-light" style="border: 1px solid rgba(0,0,0,0.15)"><i class="fa-solid fa-brush"></i></button>
                <button type="submit" class="btn btn-sm btn-primary"><i class="fa-solid fa-floppy-disk"></i></button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection