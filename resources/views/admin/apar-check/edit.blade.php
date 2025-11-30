@extends('admin.template')

@section('title', 'Edit Pengecekan APAR - ' . $apar->number_apar)

@section('breadcrumb')
    <h1 class="mt-4">Edit Pengecekan APAR</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('apar-check.index') }}" class="text-decoration-none text-secondary">Summary</a></li>
        <li class="breadcrumb-item">Edit {{ $apar->number_apar }}</li>
    </ol>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-edit me-2"></i>
                Form Edit Pengecekan APAR
            </div>
            <div class="card-body p-1 bg-light">
                <div class="form-input">
                    <form action="{{ route('apar-check.update', $aparCheck) }}" method="POST" id="checkForm">
                        @csrf
                        @method('PUT')

                        <!-- Informasi APAR -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card-body rounded-3 border">
                                    <h6 class="alert-heading">Informasi APAR</h6>
                                    <div class="row small">
                                        <div class="col-md-3">
                                            <strong>Nomor APAR:</strong><br>
                                            {{ $apar->number_apar }}
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Lokasi:</strong><br>
                                            {{ $apar->location }}
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Zona:</strong><br>
                                            {{ $apar->zone->name ?? '-' }}
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Gedung:</strong><br>
                                            {{ $apar->building->name ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Pengecekan Asli -->
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <h6 class="alert-heading"><i class="fas fa-info-circle me-1"></i>Informasi Pengecekan</h6>
                                    <div class="row small">
                                        <div class="col-md-4">
                                            <strong>Tanggal Pengecekan:</strong><br>
                                            {{ $aparCheck->created_at->format('d/m/Y H:i') }}
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Pemeriksa:</strong><br>
                                            {{ $aparCheck->user->name }}
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Status:</strong><br>
                                            {!! $aparCheck->status_badge !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Kondisi Tekanan -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label required">Kondisi Tekanan</label>
                                <div class="btn-group-vertical w-100" role="group" aria-label="Kondisi Tekanan">
                                    @foreach($pressures as $pressure)
                                        @php
                                            $badgeColor = match(strtolower($pressure->name)) {
                                                'baik', 'normal', 'bagus' => 'success',
                                                'perlu perbaikan', 'sedang diperbaiki', 'maintenance' => 'warning',
                                                'rusak', 'perlu diganti', 'ganti' => 'danger',
                                                default => 'secondary'
                                            };
                                            $isChecked = old('apar_pressure_id', $aparCheck->apar_pressure_id) == $pressure->id;
                                        @endphp
                                        <input type="radio" class="btn-check" 
                                               name="apar_pressure_id" 
                                               id="pressure_{{ $pressure->id }}" 
                                               value="{{ $pressure->id }}"
                                               {{ $isChecked ? 'checked' : '' }}
                                               required>
                                        <label class="btn btn-outline-{{ $badgeColor }} mb-1 text-start" for="pressure_{{ $pressure->id }}">
                                            {{ $pressure->name }}
                                        </label>
                                    @endforeach
                                </div>
                                @error('apar_pressure_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Periksa indikator tekanan pada APAR</div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label required">Kondisi Silinder/Tabung</label>
                                <div class="btn-group-vertical w-100" role="group" aria-label="Kondisi Silinder">
                                    @foreach($cylinders as $cylinder)
                                        @php
                                            $badgeColor = match(strtolower($cylinder->name)) {
                                                'baik', 'normal', 'bagus' => 'success',
                                                'perlu perbaikan', 'sedang diperbaiki', 'maintenance' => 'warning',
                                                'rusak', 'perlu diganti', 'ganti' => 'danger',
                                                default => 'secondary'
                                            };
                                            $isChecked = old('apar_cylinder_id', $aparCheck->apar_cylinder_id) == $cylinder->id;
                                        @endphp
                                        <input type="radio" class="btn-check" 
                                               name="apar_cylinder_id" 
                                               id="cylinder_{{ $cylinder->id }}" 
                                               value="{{ $cylinder->id }}"
                                               {{ $isChecked ? 'checked' : '' }}
                                               required>
                                        <label class="btn btn-outline-{{ $badgeColor }} mb-1 text-start" for="cylinder_{{ $cylinder->id }}">
                                            {{ $cylinder->name }}
                                        </label>
                                    @endforeach
                                </div>
                                @error('apar_cylinder_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Periksa kondisi fisik tabung APAR</div>
                            </div>
                        </div>

                        <!-- Pin dan Seal -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label required">Kondisi Pin dan Seal</label>
                                <div class="btn-group-vertical w-100" role="group" aria-label="Kondisi Pin dan Seal">
                                    @foreach($pinSeals as $pinSeal)
                                        @php
                                            $badgeColor = match(strtolower($pinSeal->name)) {
                                                'baik', 'normal', 'bagus' => 'success',
                                                'perlu perbaikan', 'sedang diperbaiki', 'maintenance' => 'warning',
                                                'rusak', 'perlu diganti', 'ganti' => 'danger',
                                                default => 'secondary'
                                            };
                                            $isChecked = old('apar_pin_seal_id', $aparCheck->apar_pin_seal_id) == $pinSeal->id;
                                        @endphp
                                        <input type="radio" class="btn-check" 
                                               name="apar_pin_seal_id" 
                                               id="pin_seal_{{ $pinSeal->id }}" 
                                               value="{{ $pinSeal->id }}"
                                               {{ $isChecked ? 'checked' : '' }}
                                               required>
                                        <label class="btn btn-outline-{{ $badgeColor }} mb-1 text-start" for="pin_seal_{{ $pinSeal->id }}">
                                            {{ $pinSeal->name }}
                                        </label>
                                    @endforeach
                                </div>
                                @error('apar_pin_seal_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Periksa pin pengaman dan segel APAR</div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label required">Kondisi Selang</label>
                                <div class="btn-group-vertical w-100" role="group" aria-label="Kondisi Selang">
                                    @foreach($hoses as $hose)
                                        @php
                                            $badgeColor = match(strtolower($hose->name)) {
                                                'baik', 'normal', 'bagus' => 'success',
                                                'perlu perbaikan', 'sedang diperbaiki', 'maintenance' => 'warning',
                                                'rusak', 'perlu diganti', 'ganti' => 'danger',
                                                default => 'secondary'
                                            };
                                            $isChecked = old('apar_hose_id', $aparCheck->apar_hose_id) == $hose->id;
                                        @endphp
                                        <input type="radio" class="btn-check" 
                                               name="apar_hose_id" 
                                               id="hose_{{ $hose->id }}" 
                                               value="{{ $hose->id }}"
                                               {{ $isChecked ? 'checked' : '' }}
                                               required>
                                        <label class="btn btn-outline-{{ $badgeColor }} mb-1 text-start" for="hose_{{ $hose->id }}">
                                            {{ $hose->name }}
                                        </label>
                                    @endforeach
                                </div>
                                @error('apar_hose_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Periksa kondisi selang APAR</div>
                            </div>
                        </div>

                        <!-- Handle dan Kondisi Umum -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label required">Kondisi Handle</label>
                                <div class="btn-group-vertical w-100" role="group" aria-label="Kondisi Handle">
                                    @foreach($handles as $handle)
                                        @php
                                            $badgeColor = match(strtolower($handle->name)) {
                                                'baik', 'normal', 'bagus' => 'success',
                                                'perlu perbaikan', 'sedang diperbaiki', 'maintenance' => 'warning',
                                                'rusak', 'perlu diganti', 'ganti' => 'danger',
                                                default => 'secondary'
                                            };
                                            $isChecked = old('apar_handle_id', $aparCheck->apar_handle_id) == $handle->id;
                                        @endphp
                                        <input type="radio" class="btn-check" 
                                               name="apar_handle_id" 
                                               id="handle_{{ $handle->id }}" 
                                               value="{{ $handle->id }}"
                                               {{ $isChecked ? 'checked' : '' }}
                                               required>
                                        <label class="btn btn-outline-{{ $badgeColor }} mb-1 text-start" for="handle_{{ $handle->id }}">
                                            {{ $handle->name }}
                                        </label>
                                    @endforeach
                                </div>
                                @error('apar_handle_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Periksa kondisi handle/katup APAR</div>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label required">Kondisi Umum APAR</label>
                                <div class="btn-group-vertical w-100" role="group" aria-label="Kondisi Umum APAR">
                                    @foreach($conditions as $condition)
                                        @php
                                            $badgeColor = match(strtolower($condition->name)) {
                                                'baik', 'normal', 'bagus' => 'success',
                                                'perlu perbaikan', 'sedang diperbaiki', 'maintenance' => 'warning',
                                                'rusak', 'perlu diganti', 'ganti' => 'danger',
                                                default => 'secondary'
                                            };
                                            $isChecked = old('extinguisher_condition_id', $aparCheck->extinguisher_condition_id) == $condition->id;
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
                                <div class="form-text">Kondisi keseluruhan APAR</div>
                            </div>
                        </div>

                        <!-- Catatan Tambahan -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <label for="notes" class="form-label">Catatan Tambahan</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" 
                                          id="notes" name="notes" rows="4" 
                                          placeholder="Masukkan catatan tambahan jika ada (opsional)">{{ old('notes', $aparCheck->notes) }}</textarea>
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
                                    <a href="{{ route('apar-check.show', $aparCheck->id) }}" class="btn btn-sm btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Detail
                                    </a>
                                    <div class="d-flex gap-2">
                                        <button type="reset" class="btn btn-sm btn-outline-warning">
                                            <i class="fas fa-undo me-1"></i> Reset
                                        </button>
                                        <button type="submit" class="btn btn-sm btn-warning" id="submitBtn">
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
                            <td><strong>Tanggal Edit:</strong></td>
                            <td>{{ now()->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Waktu Edit:</strong></td>
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

        <!-- Status Pengecekan -->
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-history me-2"></i>
                Riwayat Pengecekan
            </div>
            <div class="card-body small p-1 bg-light">
                <div class="form-input">
                    <p><strong>Dibuat:</strong> {{ $aparCheck->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Diperbarui:</strong> {{ $aparCheck->updated_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Oleh:</strong> {{ $aparCheck->user->name }}</p>
                </div>
            </div>
        </div>

        <!-- QR Code APAR -->
        <div class="card">
            <div class="card-header">
                <i class="fas fa-qrcode me-2"></i>
                QR Code APAR
            </div>
            <div class="card-body text-center p-1 bg-light">
                <div class="form-input">
                    @if($apar->qr_code_small)
                        <div class="text-center">
                            {{ $apar->qr_code_small }}
                        </div>
                        <p class="mt-2 mb-0 small">
                            <strong>{{ $apar->number_apar }}</strong><br>
                            {{ $apar->location }}
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
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Form validation (sama seperti create)
    $('#checkForm').on('submit', function(e) {
        const requiredGroups = [
            'apar_pressure_id',
            'apar_cylinder_id', 
            'apar_pin_seal_id',
            'apar_hose_id',
            'apar_handle_id',
            'extinguisher_condition_id'
        ];

        let isValid = true;
        let firstInvalidElement = null;

        requiredGroups.forEach(name => {
            const checkedInput = $(`input[name="${name}"]:checked`);
            const groupInputs = $(`input[name="${name}"]`);

            if (!checkedInput.length) {
                isValid = false;
                groupInputs.each(function() {
                    $(this).addClass('is-invalid');
                });
                if (!firstInvalidElement && groupInputs.length) {
                    firstInvalidElement = $(groupInputs[0]).closest('.btn-group-vertical');
                }
            } else {
                groupInputs.each(function() {
                    $(this).removeClass('is-invalid');
                });
            }
        });

        if (!isValid) {
            e.preventDefault();
            if (firstInvalidElement && firstInvalidElement.length) {
                $('html, body').animate({
                    scrollTop: firstInvalidElement.offset().top - 100
                }, 500);
            }
            
            Swal.fire({
                icon: 'warning',
                title: 'Form Belum Lengkap',
                text: 'Harap lengkapi semua field yang wajib diisi',
                confirmButtonColor: '#3085d6',
            });
            return;
        }

        // Disable submit button to prevent double submission
        $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Memperbarui...');
    });

    // Real-time validation
    $('input[type="radio"]').on('change', function() {
        const name = $(this).attr('name');
        if ($(`input[name="${name}"]:checked`).length) {
            $(`input[name="${name}"]`).removeClass('is-invalid');
        }
    });
});
</script>
@endpush