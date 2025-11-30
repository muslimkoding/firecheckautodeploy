@extends('admin.template')

@section('title', 'Edit Hydrant')

@section('breadcrumb')
    <h1 class="mt-4">Hydrant</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('hydrant.index') }}" class="text-decoration-none text-secondary">List</a>
        </li>
        <li class="breadcrumb-item">Edit</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('hydrant.index') }}" class="text-decoration-none text-dark"><i
                                class="fa-solid fa-arrow-left"></i> Back</a>
                    </div>
                </div>
                <div class="card-body p-1 bg-light">
                    <div class="form-input">
                        <form action="{{ route('hydrant.update', $hydrant->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            {{-- ==================== tangkap error validasi - delete soon ================== --}}
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <strong>Debug Errors:</strong>
                                    <ul>
                                        @foreach ($errors->all() as $err)
                                            <li>{{ $err }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            {{-- ==================== tangkap error validasi - delete soon ================== --}}

                            <!-- Number Hydrant -->
                            <div class="mb-3">
                                <label for="number_hydrant" class="form-label">Nomor Hydrant *</label>
                                <input type="text" class="form-control @error('number_hydrant') is-invalid @enderror"
                                    id="number_hydrant" name="number_hydrant"
                                    value="{{ old('number_hydrant', $hydrant->number_hydrant) }}"
                                    style="text-transform: uppercase;" required>
                                @error('number_hydrant')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Location -->
                            <div class="mb-3">
                                <label for="location" class="form-label">Lokasi *</label>
                                <input type="text" class="form-control @error('location') is-invalid @enderror"
                                    id="location" name="location" value="{{ old('location', $hydrant->location) }}"
                                    required>
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="is_active" id="is_active_true"
                                            value="1"
                                            {{ old('is_active', $hydrant->is_active) == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active_true">Aktif</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="is_active" id="is_active_false"
                                            value="0"
                                            {{ old('is_active', $hydrant->is_active) == 0 ? 'checked' : '' }}>
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
                                <select class="form-select @error('zone_id') is-invalid @enderror" id="zone_id"
                                    name="zone_id" required>
                                    <option value="">Pilih Zona</option>
                                    @foreach ($zones as $zone)
                                        <option value="{{ $zone->id }}"
                                            {{ old('zone_id', $hydrant->zone_id) == $zone->id ? 'selected' : '' }}>
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
                                <select name="building_id" id="building_id"
                                    class="form-select @error('building_id') is-invalid @enderror" required>
                                    <option value="">Pilih Gedung</option>
                                    @foreach ($buildings as $building)
                                        <option value="{{ $building->id }}"
                                            {{ old('building_id', $hydrant->building_id) == $building->id ? 'selected' : '' }}>
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
                                <select name="floor_id" id="floor_id"
                                    class="form-select @error('floor_id') is-invalid @enderror" required>
                                    <option value="">Pilih Lantai</option>
                                    @foreach ($floors as $floor)
                                        <option value="{{ $floor->id }}"
                                            {{ old('floor_id', $hydrant->floor_id) == $floor->id ? 'selected' : '' }}>
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
                                <select name="brand_id" id="brand_id"
                                    class="form-select @error('brand_id') is-invalid @enderror" required>
                                    <option value="">Pilih Merek</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}"
                                            {{ old('brand_id', $hydrant->brand_id) == $brand->id ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('brand_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="hydrant_type_id" class="form-label">Tipe Hydrant *</label>
                                <select name="hydrant_type_id" id="hydrant_type_id"
                                    class="form-select @error('hydrant_type_id') is-invalid @enderror" required>
                                    <option value="">Pilih Tipe</option>
                                    @foreach ($hydrantTypes as $hydrantType)
                                        <option value="{{ $hydrantType->id }}"
                                            {{ old('hydrant_type_id', $hydrant->hydrant_type_id) == $hydrantType->id ? 'selected' : '' }}>
                                            {{ $hydrantType->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('hydrant_type_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="extinguisher_condition_id" class="form-label">Kondisi Hydrant *</label>
                                <select name="extinguisher_condition_id" id="extinguisher_condition_id"
                                    class="form-select @error('extinguisher_condition_id') is-invalid @enderror" required>
                                    <option value="">Pilih Kondisi</option>
                                    @foreach ($extinguisherConditions as $extinguiserCondition)
                                        <option value="{{ $extinguiserCondition->id }}"
                                            {{ old('extinguisher_condition_id', $hydrant->extinguisher_condition_id) == $extinguiserCondition->id ? 'selected' : '' }}>
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
                                <button type="reset" class="btn btn-sm btn-light"
                                    style="border: 1px solid rgba(0,0,0,0.15)"><i class="fa-solid fa-brush"></i></button>
                                <button type="submit" class="btn btn-sm btn-primary"><i
                                        class="fa-solid fa-floppy-disk"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
