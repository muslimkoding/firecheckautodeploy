@extends('admin.template')

@section('title', 'Pengecekan Hydrant - ' . $hydrant->number_hydrant)

@section('breadcrumb')
    <h1 class="mt-4">Pengecekan Hydrant</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('hydrant-check.scan') }}" class="text-decoration-none text-secondary">Scan Hydrant</a></li>
        <li class="breadcrumb-item ">Pengecekan {{ $hydrant->number_hydrant }}</li>
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
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-clipboard-check me-2"></i>
                Form Pengecekan Hydrant
            </div>
            <div class="card-body p-1 bg-light">
                <div class="form-input">
                    <form action="{{ route('hydrant-check.store', $hydrant->id) }}" method="POST" id="checkForm">
                        @csrf
    
                        <!-- Informasi Hydrant -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card-body rounded-3 border">
                                    <h6 class="alert-heading">Informasi Hydrant</h6>
                                    <div class="row small">
                                        <div class="col-md-3">
                                            <strong>Nomor Hydrant:</strong><br>
                                            {{ $hydrant->number_hydrant }}
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Lokasi:</strong><br>
                                            {{ $hydrant->location }}
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Zona:</strong><br>
                                            {{ $hydrant->zone->name ?? '-' }}
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Gedung:</strong><br>
                                            {{ $hydrant->building->name ?? '-' }}
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Tipe Hydrant:</strong><br>
                                            {{ $hydrant->hydrantType->name ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
    
                        {{-- <!-- Kondisi Pintu -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="hydrant_door_id" class="form-label required">Kondisi Pintu Box</label>
                                <select class="form-select @error('hydrant_door_id') is-invalid @enderror" 
                                        id="hydrant_door_id" name="hydrant_door_id" required>
                                    <option value="">Pilih Kondisi Pintu</option>
                                    @foreach($hydrantDoors as $hydrantDoor)
                                        <option value="{{ $hydrantDoor->id }}" 
                                            {{ old('hydrant_door_id') == $hydrantDoor->id ? 'selected' : '' }}>
                                            {{ $hydrantDoor->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('hydrant_door_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Periksa kondisi pintu box Hydrant</div>
                            </div>
                            <div class="col-md-6">
                                <label for="hydrant_coupling_id" class="form-label required">Kondisi Coupling</label>
                                <select class="form-select @error('hydrant_coupling_id') is-invalid @enderror" 
                                        id="hydrant_coupling_id" name="hydrant_coupling_id" required>
                                    <option value="">Pilih Kondisi Coupling</option>
                                    @foreach($hydrantCouplings as $hydrantCoupling)
                                        <option value="{{ $hydrantCoupling->id }}" 
                                            {{ old('hydrant_coupling_id') == $hydrantCoupling->id ? 'selected' : '' }}>
                                            {{ $hydrantCoupling->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('hydrant_coupling_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Periksa kondisi coupling hydrant</div>
                            </div>
                        </div>
    
                        <!-- Main valve dan selang -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="hydrant_main_valve_id" class="form-label required">Kondisi Main Valve</label>
                                <select class="form-select @error('hydrant_main_valve_id') is-invalid @enderror" 
                                        id="hydrant_main_valve_id" name="hydrant_main_valve_id" required>
                                    <option value="">Pilih Kondisi Main Valve</option>
                                    @foreach($hydrantMainValves as $hydrantMainValve)
                                        <option value="{{ $hydrantMainValve->id }}" 
                                            {{ old('hydrant_main_valve_id') == $hydrantMainValve->id ? 'selected' : '' }}>
                                            {{ $hydrantMainValve->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('hydrant_main_valve_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Periksa Main Valve</div>
                            </div>
                            <div class="col-md-6">
                                <label for="hydrant_hose_id" class="form-label required">Kondisi Selang</label>
                                <select class="form-select @error('hydrant_hose_id') is-invalid @enderror" 
                                        id="hydrant_hose_id" name="hydrant_hose_id" required>
                                    <option value="">Pilih Kondisi Selang</option>
                                    @foreach($hydrantHoses as $hydrantHose)
                                        <option value="{{ $hydrantHose->id }}" 
                                            {{ old('hydrant_hose_id') == $hydrantHose->id ? 'selected' : '' }}>
                                            {{ $hydrantHose->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('hydrant_hose_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Periksa kondisi selang Hydrant</div>
                            </div>
                        </div>
    
                        <!-- nozzle dan marking -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="hydrant_nozzle_id" class="form-label required">Kondisi Nozzle</label>
                                <select class="form-select @error('hydrant_nozzle_id') is-invalid @enderror" 
                                        id="hydrant_nozzle_id" name="hydrant_nozzle_id" required>
                                    <option value="">Pilih Kondisi Nozzle</option>
                                    @foreach($hydrantNozzles as $hydrantNozzle)
                                        <option value="{{ $hydrantNozzle->id }}" 
                                            {{ old('hydrant_nozzle_id') == $hydrantNozzle->id ? 'selected' : '' }}>
                                            {{ $hydrantNozzle->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('hydrant_nozzle_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Periksa Nozzle</div>
                            </div>
                            <div class="col-md-6">
                                <label for="hydrant_safety_marking_id" class="form-label required">Cek ketersediaan marking safety</label>
                                <select class="form-select @error('hydrant_safety_marking_id') is-invalid @enderror" 
                                        id="hydrant_safety_marking_id" name="hydrant_safety_marking_id" required>
                                    <option value="">Pilih Kondisi Safety Marking</option>
                                    @foreach($hydrantSafetyMarkings as $hydrantSafetyMarking)
                                        <option value="{{ $hydrantSafetyMarking->id }}" 
                                            {{ old('hydrant_safety_marking_id') == $hydrantSafetyMarking->id ? 'selected' : '' }}>
                                            {{ $hydrantSafetyMarking->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('hydrant_safety_marking_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Periksa ketersediaan marking safety</div>
                            </div>
                        </div>
    
                        <!-- guide dan Kondisi Umum -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="hydrant_guide_id" class="form-label required">Ketersediaan Panduan Penggunaan</label>
                                <select class="form-select @error('hydrant_guide_id') is-invalid @enderror" 
                                        id="hydrant_guide_id" name="hydrant_guide_id" required>
                                    <option value="">Pilih Ketersediaan Panduan Penggunaan</option>
                                    @foreach($hydrantGuides as $hydrantGuide)
                                        <option value="{{ $hydrantGuide->id }}" 
                                            {{ old('hydrant_guide_id') == $hydrantGuide->id ? 'selected' : '' }}>
                                            {{ $hydrantGuide->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('hydrant_guide_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Periksa ketersediaan panduan penggunaan</div>
                            </div>
                            <div class="col-md-6">
                                <label for="extinguisher_condition_id" class="form-label required">Kondisi Umum Hydrant</label>
                                <select class="form-select @error('extinguisher_condition_id') is-invalid @enderror" 
                                        id="extinguisher_condition_id" name="extinguisher_condition_id" required>
                                    <option value="">Pilih Kondisi Umum</option>
                                    @foreach($conditions as $condition)
                                        <option value="{{ $condition->id }}" 
                                            {{ old('extinguisher_condition_id') == $condition->id ? 'selected' : '' }}>
                                            {{ $condition->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('extinguisher_condition_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Kondisi keseluruhan Hydrant</div>
                            </div>
                        </div>
    
                        <!-- Catatan Tambahan -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <label for="notes" class="form-label">Catatan Tambahan</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" 
                                          id="notes" name="notes" rows="4" 
                                          placeholder="Masukkan catatan tambahan jika ada (opsional)">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Catatan kerusakan, keanehan, atau hal penting lainnya</div>
                            </div>
                        </div> --}}

                        <!-- Kondisi Pintu -->
<div class="row mb-3">
    <div class="col-md-6">
        <label class="form-label required">Kondisi Pintu Box</label>
        <div class="btn-group-vertical w-100" role="group" aria-label="Kondisi Pintu Box">
            @foreach($hydrantDoors as $hydrantDoor)
                @php
                    $badgeColor = match(strtolower($hydrantDoor->name)) {
                        'baik', 'normal', 'bagus', 'lengkap' => 'success',
                        'perlu perbaikan', 'sedang diperbaiki', 'maintenance', 'kurang lengkap' => 'warning',
                        'rusak', 'perlu diganti', 'ganti', 'tidak lengkap' => 'danger',
                        default => 'secondary'
                    };
                @endphp
                <input type="radio" class="btn-check" 
                       name="hydrant_door_id" 
                       id="door_{{ $hydrantDoor->id }}" 
                       value="{{ $hydrantDoor->id }}"
                       {{ old('hydrant_door_id') == $hydrantDoor->id ? 'checked' : '' }}
                       required>
                <label class="btn btn-outline-{{ $badgeColor }} mb-1 text-start" for="door_{{ $hydrantDoor->id }}">
                    {{ $hydrantDoor->name }}
                </label>
            @endforeach
        </div>
        @error('hydrant_door_id')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
        <div class="form-text">Periksa kondisi pintu box Hydrant</div>
    </div>
    
    <div class="col-md-6">
        <label class="form-label required">Kondisi Coupling</label>
        <div class="btn-group-vertical w-100" role="group" aria-label="Kondisi Coupling">
            @foreach($hydrantCouplings as $hydrantCoupling)
                @php
                    $badgeColor = match(strtolower($hydrantCoupling->name)) {
                        'baik', 'normal', 'bagus', 'kokoh' => 'success',
                        'perlu perbaikan', 'sedang diperbaiki', 'maintenance', 'longgar' => 'warning',
                        'rusak', 'perlu diganti', 'ganti', 'lepas' => 'danger',
                        default => 'secondary'
                    };
                @endphp
                <input type="radio" class="btn-check" 
                       name="hydrant_coupling_id" 
                       id="coupling_{{ $hydrantCoupling->id }}" 
                       value="{{ $hydrantCoupling->id }}"
                       {{ old('hydrant_coupling_id') == $hydrantCoupling->id ? 'checked' : '' }}
                       required>
                <label class="btn btn-outline-{{ $badgeColor }} mb-1 text-start" for="coupling_{{ $hydrantCoupling->id }}">
                    {{ $hydrantCoupling->name }}
                </label>
            @endforeach
        </div>
        @error('hydrant_coupling_id')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
        <div class="form-text">Periksa kondisi coupling hydrant</div>
    </div>
</div>

<!-- Main valve dan selang -->
<div class="row mb-3">
    <div class="col-md-6">
        <label class="form-label required">Kondisi Main Valve</label>
        <div class="btn-group-vertical w-100" role="group" aria-label="Kondisi Main Valve">
            @foreach($hydrantMainValves as $hydrantMainValve)
                @php
                    $badgeColor = match(strtolower($hydrantMainValve->name)) {
                        'baik', 'normal', 'bagus', 'berfungsi' => 'success',
                        'perlu perbaikan', 'sedang diperbaiki', 'maintenance', 'macet' => 'warning',
                        'rusak', 'perlu diganti', 'ganti', 'tidak berfungsi' => 'danger',
                        default => 'secondary'
                    };
                @endphp
                <input type="radio" class="btn-check" 
                       name="hydrant_main_valve_id" 
                       id="main_valve_{{ $hydrantMainValve->id }}" 
                       value="{{ $hydrantMainValve->id }}"
                       {{ old('hydrant_main_valve_id') == $hydrantMainValve->id ? 'checked' : '' }}
                       required>
                <label class="btn btn-outline-{{ $badgeColor }} mb-1 text-start" for="main_valve_{{ $hydrantMainValve->id }}">
                    {{ $hydrantMainValve->name }}
                </label>
            @endforeach
        </div>
        @error('hydrant_main_valve_id')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
        <div class="form-text">Periksa Main Valve</div>
    </div>
    
    <div class="col-md-6">
        <label class="form-label required">Kondisi Selang</label>
        <div class="btn-group-vertical w-100" role="group" aria-label="Kondisi Selang">
            @foreach($hydrantHoses as $hydrantHose)
                @php
                    $badgeColor = match(strtolower($hydrantHose->name)) {
                        'baik', 'normal', 'bagus', 'lentur' => 'success',
                        'perlu perbaikan', 'sedang diperbaiki', 'maintenance', 'kaku' => 'warning',
                        'rusak', 'perlu diganti', 'ganti', 'bocor' => 'danger',
                        default => 'secondary'
                    };
                @endphp
                <input type="radio" class="btn-check" 
                       name="hydrant_hose_id" 
                       id="hose_{{ $hydrantHose->id }}" 
                       value="{{ $hydrantHose->id }}"
                       {{ old('hydrant_hose_id') == $hydrantHose->id ? 'checked' : '' }}
                       required>
                <label class="btn btn-outline-{{ $badgeColor }} mb-1 text-start" for="hose_{{ $hydrantHose->id }}">
                    {{ $hydrantHose->name }}
                </label>
            @endforeach
        </div>
        @error('hydrant_hose_id')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
        <div class="form-text">Periksa kondisi selang Hydrant</div>
    </div>
</div>

<!-- nozzle dan marking -->
<div class="row mb-3">
    <div class="col-md-6">
        <label class="form-label required">Kondisi Nozzle</label>
        <div class="btn-group-vertical w-100" role="group" aria-label="Kondisi Nozzle">
            @foreach($hydrantNozzles as $hydrantNozzle)
                @php
                    $badgeColor = match(strtolower($hydrantNozzle->name)) {
                        'baik', 'normal', 'bagus', 'bersih' => 'success',
                        'perlu perbaikan', 'sedang diperbaiki', 'maintenance', 'kotor' => 'warning',
                        'rusak', 'perlu diganti', 'ganti', 'tersumbat' => 'danger',
                        default => 'secondary'
                    };
                @endphp
                <input type="radio" class="btn-check" 
                       name="hydrant_nozzle_id" 
                       id="nozzle_{{ $hydrantNozzle->id }}" 
                       value="{{ $hydrantNozzle->id }}"
                       {{ old('hydrant_nozzle_id') == $hydrantNozzle->id ? 'checked' : '' }}
                       required>
                <label class="btn btn-outline-{{ $badgeColor }} mb-1 text-start" for="nozzle_{{ $hydrantNozzle->id }}">
                    {{ $hydrantNozzle->name }}
                </label>
            @endforeach
        </div>
        @error('hydrant_nozzle_id')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
        <div class="form-text">Periksa Nozzle</div>
    </div>
    
    <div class="col-md-6">
        <label class="form-label required">Cek ketersediaan marking safety</label>
        <div class="btn-group-vertical w-100" role="group" aria-label="Safety Marking">
            @foreach($hydrantSafetyMarkings as $hydrantSafetyMarking)
                @php
                    $badgeColor = match(strtolower($hydrantSafetyMarking->name)) {
                        'tersedia', 'lengkap', 'jelas', 'baik' => 'success',
                        'kurang lengkap', 'sedang dipasang', 'faded' => 'warning',
                        'tidak tersedia', 'hilang', 'rusak' => 'danger',
                        default => 'secondary'
                    };
                @endphp
                <input type="radio" class="btn-check" 
                       name="hydrant_safety_marking_id" 
                       id="safety_marking_{{ $hydrantSafetyMarking->id }}" 
                       value="{{ $hydrantSafetyMarking->id }}"
                       {{ old('hydrant_safety_marking_id') == $hydrantSafetyMarking->id ? 'checked' : '' }}
                       required>
                <label class="btn btn-outline-{{ $badgeColor }} mb-1 text-start" for="safety_marking_{{ $hydrantSafetyMarking->id }}">
                    {{ $hydrantSafetyMarking->name }}
                </label>
            @endforeach
        </div>
        @error('hydrant_safety_marking_id')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
        <div class="form-text">Periksa ketersediaan marking safety</div>
    </div>
</div>

<!-- guide dan Kondisi Umum -->
<div class="row mb-3">
    <div class="col-md-6">
        <label class="form-label required">Ketersediaan Panduan Penggunaan</label>
        <div class="btn-group-vertical w-100" role="group" aria-label="Panduan Penggunaan">
            @foreach($hydrantGuides as $hydrantGuide)
                @php
                    $badgeColor = match(strtolower($hydrantGuide->name)) {
                        'tersedia', 'lengkap', 'jelas', 'terpasang' => 'success',
                        'kurang lengkap', 'sedang dicetak', 'faded' => 'warning',
                        'tidak tersedia', 'hilang', 'rusak' => 'danger',
                        default => 'secondary'
                    };
                @endphp
                <input type="radio" class="btn-check" 
                       name="hydrant_guide_id" 
                       id="guide_{{ $hydrantGuide->id }}" 
                       value="{{ $hydrantGuide->id }}"
                       {{ old('hydrant_guide_id') == $hydrantGuide->id ? 'checked' : '' }}
                       required>
                <label class="btn btn-outline-{{ $badgeColor }} mb-1 text-start" for="guide_{{ $hydrantGuide->id }}">
                    {{ $hydrantGuide->name }}
                </label>
            @endforeach
        </div>
        @error('hydrant_guide_id')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
        <div class="form-text">Periksa ketersediaan panduan penggunaan</div>
    </div>
    
    <div class="col-md-6">
        <label class="form-label required">Kondisi Umum Hydrant</label>
        <div class="btn-group-vertical w-100" role="group" aria-label="Kondisi Umum Hydrant">
            @foreach($conditions as $condition)
                @php
                    $badgeColor = match(strtolower($condition->name)) {
                        'baik', 'normal', 'bagus', 'siap pakai' => 'success',
                        'perlu perbaikan', 'sedang diperbaiki', 'maintenance' => 'warning',
                        'rusak', 'perlu diganti', 'ganti', 'tidak layak' => 'danger',
                        default => 'secondary'
                    };
                @endphp
                <input type="radio" class="btn-check" 
                       name="extinguisher_condition_id" 
                       id="condition_{{ $condition->id }}" 
                       value="{{ $condition->id }}"
                       {{ old('extinguisher_condition_id') == $condition->id ? 'checked' : '' }}
                       required>
                <label class="btn btn-outline-{{ $badgeColor }} mb-1 text-start" for="condition_{{ $condition->id }}">
                    {{ $condition->name }}
                </label>
            @endforeach
        </div>
        @error('extinguisher_condition_id')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
        <div class="form-text">Kondisi keseluruhan Hydrant</div>
    </div>
</div>

<!-- Catatan Tambahan -->
<div class="row mb-4">
    <div class="col-12">
        <label for="notes" class="form-label">Catatan Tambahan</label>
        <textarea class="form-control @error('notes') is-invalid @enderror" 
                  id="notes" name="notes" rows="4" 
                  placeholder="Masukkan catatan tambahan jika ada (opsional)">{{ old('notes') }}</textarea>
        @error('notes')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div class="form-text">Catatan kerusakan, keanehan, atau hal penting lainnya</div>
    </div>
</div>
    
                        <!-- Action Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('hydrant-check.scan') }}" class="btn btn-sm btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i> Kembali
                                    </a>
                                    <div class="d-flex gap-2">
                                        <button type="reset" class="btn btn-sm btn-outline-warning">
                                            <i class="fas fa-undo me-1"></i> Reset
                                        </button>
                                        <button type="submit" class="btn btn-sm btn-primary" id="submitBtn">
                                            <i class="fas fa-save me-1"></i> Simpan Pengecekan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Informasi Pemeriksa -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-user me-2"></i>
                Informasi Pemeriksa
            </div>
            <div class="card-body p-1 bg-light">

                <div class="form-input">
                    <div class="text-center mb-3">
                        @if(auth()->user()->image)
                            <img src="{{ asset('storage/' . auth()->user()->image) }}" 
                                 alt="{{ auth()->user()->name }}" 
                                 class="rounded-circle" width="80" height="80" style="object-fit: cover;">
                        @else
                            <div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center" 
                                 style="width: 80px; height: 80px;">
                                <i class="fas fa-user text-white fa-2x"></i>
                            </div>
                        @endif
                        <h6 class="mt-2 mb-0">{{ auth()->user()->name }}</h6>
                        <small class="text-muted">{{ auth()->user()->email }}</small>
                    </div>
                    <table class="table table-sm small">
                        <tr>
                            <td><strong>Tanggal:</strong></td>
                            <td>{{ now()->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Waktu:</strong></td>
                            <td>{{ now()->format('H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Group:</strong></td>
                            <td>{{ auth()->user()->group->name ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Panduan Pengecekan -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-lightbulb me-2"></i>
                Panduan Pengecekan
            </div>
            <div class="card-body small p-1 bg-light">
                <div class="form-input">
                    <h6>Yang harus diperiksa:</h6>
                <ul class="mb-0">
                    <li>Pintu box mudah dibuka dan tertutup rapat</li>
                    <li>Coupling tidak longgar dan berkarat</li>
                    <li>Main valve berfungsi dengan baik</li>
                    <li>Selang tidak retak, lentur, dan tidak bocor</li>
                    <li>Nozzle bersih dan tidak tersumbat</li>
                    <li>Marking safety jelas dan lengkap</li>
                    <li>Panduan penggunaan tersedia</li>
                </ul>
                </div>
            </div>
        </div>

        <!-- QR Code Hydrant -->
        <div class="card">
            <div class="card-header">
                <i class="fas fa-qrcode me-2"></i>
                QR Code Hydrant
            </div>
            <div class="card-body text-center p-1 bg-light">
                <div class="form-input">
                    @if($hydrant->qr_code_small)
                   
                         <div class="text-center">{{ $hydrant->qr_code_small }}</div>
                    <p class="mt-2 mb-0 small">
                        <strong>{{ $hydrant->number_hydrant }}</strong><br>
                        {{ $hydrant->location }}
                    </p>
                @else
                    <div class="text-muted">
                        <i class="fas fa-qrcode fa-3x mb-2"></i>
                        <p>QR Code tidak tersedia</p>
                    </div>
                @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.required::after {
    content: " *";
    color: #dc3545;
}

.card-header {
    font-weight: 600;
}

.form-text {
    font-size: 0.875em;
    color: #6c757d;
}

.alert {
    border-left: 4px solid #0d6efd;
}

/* Custom styling for form */
.form-select:focus,
.form-control:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}
</style>
@endpush

@push('scripts')
<script src="{{ asset('assets/sweetalert.js') }}"></script>

<script>
    $(document).ready(function() {
    // Define required fields
    const requiredFields = [
        'hydrant_door_id',
        'hydrant_coupling_id', 
        'hydrant_main_valve_id',
        'hydrant_hose_id',
        'hydrant_nozzle_id',
        'hydrant_safety_marking_id',
        'hydrant_guide_id',
        'extinguisher_condition_id'
    ];

    // Form validation
    $('#checkForm').on('submit', function(e) {
        let isValid = true;
        let firstInvalidElement = null;

        // Reset all invalid states first
        $('.btn-group-vertical').removeClass('border-danger');

        requiredFields.forEach(fieldName => {
            const checkedInput = $(`input[name="${fieldName}"]:checked`);
            const radioGroup = $(`input[name="${fieldName}"]`).first().closest('.btn-group-vertical');

            if (!checkedInput.length) {
                isValid = false;
                // Add red border to the radio group
                radioGroup.addClass('border border-danger rounded');
                
                if (!firstInvalidElement) {
                    firstInvalidElement = radioGroup;
                }
            }
        });

        if (!isValid) {
            e.preventDefault();
            
            // Scroll to first invalid element
            if (firstInvalidElement && firstInvalidElement.length) {
                $('html, body').animate({
                    scrollTop: firstInvalidElement.offset().top - 100
                }, 500);
            }
            
            // Show alert
            Swal.fire({
                icon: 'warning',
                title: 'Form Belum Lengkap',
                text: 'Harap lengkapi semua field yang wajib diisi',
                confirmButtonColor: '#3085d6',
            });
            return;
        }

        // Disable submit button to prevent double submission
        $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...');
    });

    // Real-time validation for radio buttons
    $('input[type="radio"]').on('change', function() {
        const fieldName = $(this).attr('name');
        const radioGroup = $(this).closest('.btn-group-vertical');
        
        // Remove invalid styling when a radio is selected
        radioGroup.removeClass('border border-danger rounded');
    });

    // Reset form handler
    $('button[type="reset"]').on('click', function() {
        // Reset all radio button styling
        $('.btn-group-vertical').removeClass('border border-danger rounded');
        
        // Re-enable submit button if it was disabled
        $('#submitBtn').prop('disabled', false).html('<i class="fas fa-save me-1"></i> Simpan Pengecekan');
    });

    // Optional: Auto-enable submit button after 10 seconds if still disabled (safety measure)
    setTimeout(function() {
        if ($('#submitBtn').prop('disabled')) {
            $('#submitBtn').prop('disabled', false).html('<i class="fas fa-save me-1"></i> Simpan Pengecekan');
            console.log('Submit button auto-enabled after timeout');
        }
    }, 10000); // 10 seconds timeout

    // Optional: Handle browser back/forward button
    $(window).on('pageshow', function() {
        $('#submitBtn').prop('disabled', false).html('<i class="fas fa-save me-1"></i> Simpan Pengecekan');
    });
});
// $(document).ready(function() {
//     // Form validation
//     $('#checkForm').on('submit', function(e) {
//         const requiredGroups = [
//             'hydrant_door_id',
//             'hydrant_coupling_id', 
//             'hydrant_main_valve_id',
//             'hydrant_hose_id',
//             'hydrant_nozzle_id',
//             'hydrant_safety_marking_id',
//             'hydrant_guide_id',
//             'extinguisher_condition_id'
//         ];

//         let isValid = true;
//         let firstInvalidElement = null;

//         requiredGroups.forEach(name => {
//             const checkedInput = $(`input[name="${name}"]:checked`);
//             const groupInputs = $(`input[name="${name}"]`);

//             if (!checkedInput.length) {
//                 isValid = false;
//                 groupInputs.each(function() {
//                     $(this).addClass('is-invalid');
//                 });
//                 if (!firstInvalidElement && groupInputs.length) {
//                     firstInvalidElement = $(groupInputs[0]).closest('.btn-group-vertical');
//                 }
//             } else {
//                 groupInputs.each(function() {
//                     $(this).removeClass('is-invalid');
//                 });
//             }
//         });

//         if (!isValid) {
//             e.preventDefault();
//             if (firstInvalidElement && firstInvalidElement.length) {
//                 $('html, body').animate({
//                     scrollTop: firstInvalidElement.offset().top - 100
//                 }, 500);
//             }
            
//             Swal.fire({
//                 icon: 'warning',
//                 title: 'Form Belum Lengkap',
//                 text: 'Harap lengkapi semua field yang wajib diisi',
//                 confirmButtonColor: '#3085d6',
//             });
//             return;
//         }

//         // Disable submit button to prevent double submission
//         $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...');
//     });

//     // Real-time validation
//     $('input[type="radio"]').on('change', function() {
//         const name = $(this).attr('name');
//         if ($(`input[name="${name}"]:checked`).length) {
//             $(`input[name="${name}"]`).removeClass('is-invalid');
//         }
//     });

//     // Auto-save draft (optional)
//     let draftTimer;
//     $('select, textarea').on('change input', function() {
//         clearTimeout(draftTimer);
//         draftTimer = setTimeout(saveDraft, 2000);
//     });

//     function saveDraft() {
//         // Implement auto-save draft functionality here if needed
//         console.log('Auto-saving draft...');
//     }

//     // Load saved draft on page load
//     function loadDraft() {
//         // Implement load draft functionality here if needed
//         console.log('Loading draft...');
//     }

//     // Initialize
//     loadDraft();
// });
</script>
@endpush