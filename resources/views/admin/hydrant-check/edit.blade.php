@extends('admin.template')

@section('title', 'Edit Pengecekan Hydrant - ' . $hydrantCheck->hydrant->number_hydrant)

@section('breadcrumb')
    <h1 class="mt-4">Edit Pengecekan Hydrant</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('hydrant-check.to-check') }}" class="text-decoration-none text-secondary">Data Pengecekan</a></li>
        <li class="breadcrumb-item">Edit {{ $hydrantCheck->hydrant->number_hydrant }}</li>
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
                <i class="fas fa-edit me-2"></i>
                Form Edit Pengecekan Hydrant
            </div>
            <div class="card-body p-1 bg-light">
                <div class="form-input">
                    <form action="{{ route('hydrant-check.update', $hydrantCheck->id) }}" method="POST" id="checkForm">
                        @csrf
                        @method('PUT')
    
                        <!-- Informasi Hydrant -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card-body rounded-3 border">
                                    <h6 class="alert-heading">Informasi Hydrant</h6>
                                    <div class="row small">
                                        <div class="col-md-3">
                                            <strong>Nomor Hydrant:</strong><br>
                                            {{ $hydrantCheck->hydrant->number_hydrant }}
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Lokasi:</strong><br>
                                            {{ $hydrantCheck->hydrant->location }}
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Zona:</strong><br>
                                            {{ $hydrantCheck->hydrant->zone->name ?? '-' }}
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Gedung:</strong><br>
                                            {{ $hydrantCheck->hydrant->building->name ?? '-' }}
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Tipe Hydrant:</strong><br>
                                            {{ $hydrantCheck->hydrant->hydrantType->name ?? '-' }}
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Tanggal Pengecekan:</strong><br>
                                            {{ \Carbon\Carbon::parse($hydrantCheck->date_check)->format('d/m/Y') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
    
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
                                            $isChecked = old('hydrant_door_id', $hydrantCheck->hydrant_door_id) == $hydrantDoor->id;
                                        @endphp
                                        <input type="radio" class="btn-check" 
                                               name="hydrant_door_id" 
                                               id="door_{{ $hydrantDoor->id }}" 
                                               value="{{ $hydrantDoor->id }}"
                                               {{ $isChecked ? 'checked' : '' }}
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
                                            $isChecked = old('hydrant_coupling_id', $hydrantCheck->hydrant_coupling_id) == $hydrantCoupling->id;
                                        @endphp
                                        <input type="radio" class="btn-check" 
                                               name="hydrant_coupling_id" 
                                               id="coupling_{{ $hydrantCoupling->id }}" 
                                               value="{{ $hydrantCoupling->id }}"
                                               {{ $isChecked ? 'checked' : '' }}
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
                                            $isChecked = old('hydrant_main_valve_id', $hydrantCheck->hydrant_main_valve_id) == $hydrantMainValve->id;
                                        @endphp
                                        <input type="radio" class="btn-check" 
                                               name="hydrant_main_valve_id" 
                                               id="main_valve_{{ $hydrantMainValve->id }}" 
                                               value="{{ $hydrantMainValve->id }}"
                                               {{ $isChecked ? 'checked' : '' }}
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
                                            $isChecked = old('hydrant_hose_id', $hydrantCheck->hydrant_hose_id) == $hydrantHose->id;
                                        @endphp
                                        <input type="radio" class="btn-check" 
                                               name="hydrant_hose_id" 
                                               id="hose_{{ $hydrantHose->id }}" 
                                               value="{{ $hydrantHose->id }}"
                                               {{ $isChecked ? 'checked' : '' }}
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
                                            $isChecked = old('hydrant_nozzle_id', $hydrantCheck->hydrant_nozzle_id) == $hydrantNozzle->id;
                                        @endphp
                                        <input type="radio" class="btn-check" 
                                               name="hydrant_nozzle_id" 
                                               id="nozzle_{{ $hydrantNozzle->id }}" 
                                               value="{{ $hydrantNozzle->id }}"
                                               {{ $isChecked ? 'checked' : '' }}
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
                                            $isChecked = old('hydrant_safety_marking_id', $hydrantCheck->hydrant_safety_marking_id) == $hydrantSafetyMarking->id;
                                        @endphp
                                        <input type="radio" class="btn-check" 
                                               name="hydrant_safety_marking_id" 
                                               id="safety_marking_{{ $hydrantSafetyMarking->id }}" 
                                               value="{{ $hydrantSafetyMarking->id }}"
                                               {{ $isChecked ? 'checked' : '' }}
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
                                            $isChecked = old('hydrant_guide_id', $hydrantCheck->hydrant_guide_id) == $hydrantGuide->id;
                                        @endphp
                                        <input type="radio" class="btn-check" 
                                               name="hydrant_guide_id" 
                                               id="guide_{{ $hydrantGuide->id }}" 
                                               value="{{ $hydrantGuide->id }}"
                                               {{ $isChecked ? 'checked' : '' }}
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
                                            $isChecked = old('extinguisher_condition_id', $hydrantCheck->extinguisher_condition_id) == $condition->id;
                                        @endphp
                                        <input type="radio" class="btn-check" 
                                               name="extinguisher_condition_id" 
                                               id="condition_{{ $condition->id }}" 
                                               value="{{ $condition->id }}"
                                               {{ $isChecked ? 'checked' : '' }}
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
                                          placeholder="Masukkan catatan tambahan jika ada (opsional)">{{ old('notes', $hydrantCheck->notes) }}</textarea>
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
                                    <a href="{{ route('hydrant-check.index') }}" class="btn btn-sm btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i> Kembali
                                    </a>
                                    <div class="d-flex gap-2">
                                        <button type="reset" class="btn btn-sm btn-outline-warning">
                                            <i class="fas fa-undo me-1"></i> Reset
                                        </button>
                                        <button type="submit" class="btn btn-sm btn-primary" id="submitBtn">
                                            <i class="fas fa-save me-1"></i> Update Pengecekan
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
                        @if($hydrantCheck->user->image)
                            <img src="{{ asset('storage/' . $hydrantCheck->user->image) }}" 
                                 alt="{{ $hydrantCheck->user->name }}" 
                                 class="rounded-circle" width="80" height="80" style="object-fit: cover;">
                        @else
                            <div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center" 
                                 style="width: 80px; height: 80px;">
                                <i class="fas fa-user text-white fa-2x"></i>
                            </div>
                        @endif
                        <h6 class="mt-2 mb-0">{{ $hydrantCheck->user->name }}</h6>
                        <small class="text-muted">{{ $hydrantCheck->user->email }}</small>
                    </div>
                    <table class="table table-sm small">
                        <tr>
                            <td><strong>Tanggal:</strong></td>
                            <td>{{ \Carbon\Carbon::parse($hydrantCheck->date_check)->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Group:</strong></td>
                            <td>{{ $hydrantCheck->user->group->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Diperbarui:</strong></td>
                            <td>{{ \Carbon\Carbon::parse($hydrantCheck->updated_at)->format('d/m/Y H:i') }}</td>
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
                    @if($hydrantCheck->hydrant->qr_code_small)
                         <div class="text-center">{{ $hydrantCheck->hydrant->qr_code_small }}</div>
                        <p class="mt-2 mb-0 small">
                            <strong>{{ $hydrantCheck->hydrant->number_hydrant }}</strong><br>
                            {{ $hydrantCheck->hydrant->location }}
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
.btn-check:checked + .btn {
    border-width: 2px;
    font-weight: 600;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Form validation
    $('#checkForm').on('submit', function(e) {
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

        let isValid = true;
        let firstInvalidField = null;

        requiredFields.forEach(field => {
            const isChecked = $(`input[name="${field}"]:checked`).length > 0;
            if (!isChecked) {
                isValid = false;
                $(`input[name="${field}"]`).first().closest('.btn-group-vertical').addClass('border border-danger rounded');
                if (!firstInvalidField) {
                    firstInvalidField = $(`input[name="${field}"]`).first().closest('.btn-group-vertical');
                }
            } else {
                $(`input[name="${field}"]`).first().closest('.btn-group-vertical').removeClass('border border-danger rounded');
            }
        });

        if (!isValid) {
            e.preventDefault();
            // Scroll to first invalid field
            $('html, body').animate({
                scrollTop: firstInvalidField.offset().top - 100
            }, 500);
            
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
        $(`input[name="${fieldName}"]`).first().closest('.btn-group-vertical').removeClass('border border-danger rounded');
    });
});
</script>
@endpush