@extends('admin.template')

@section('title', 'Pengecekan APAR - ' . $apar->number_apar)

@section('breadcrumb')
    <h1 class="mt-4">Pengecekan APAR</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('apar-check.scan') }}" class="text-decoration-none text-secondary">Scan APAR</a></li>
        <li class="breadcrumb-item ">Pengecekan {{ $apar->number_apar }}</li>
    </ol>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-clipboard-check me-2"></i>
                Form Pengecekan APAR
            </div>
            <div class="card-body p-1 bg-light">
                <div class="form-input">
                    <form action="{{ route('apar-check.store', $apar->id) }}" method="POST" id="checkForm">
                        @csrf
    
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
    
                        {{-- <!-- Kondisi Tekanan -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="apar_pressure_id" class="form-label required">Kondisi Tekanan</label>
                                <select class="form-select @error('apar_pressure_id') is-invalid @enderror" 
                                        id="apar_pressure_id" name="apar_pressure_id" required>
                                    <option value="">Pilih Kondisi Tekanan</option>
                                    @foreach($pressures as $pressure)
                                        <option value="{{ $pressure->id }}" 
                                            {{ old('apar_pressure_id') == $pressure->id ? 'selected' : '' }}>
                                            {{ $pressure->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('apar_pressure_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Periksa indikator tekanan pada APAR</div>
                            </div>
                            <div class="col-md-6">
                                <label for="apar_cylinder_id" class="form-label required">Kondisi Silinder/Tabung</label>
                                <select class="form-select @error('apar_cylinder_id') is-invalid @enderror" 
                                        id="apar_cylinder_id" name="apar_cylinder_id" required>
                                    <option value="">Pilih Kondisi Silinder</option>
                                    @foreach($cylinders as $cylinder)
                                        <option value="{{ $cylinder->id }}" 
                                            {{ old('apar_cylinder_id') == $cylinder->id ? 'selected' : '' }}>
                                            {{ $cylinder->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('apar_cylinder_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Periksa kondisi fisik tabung APAR</div>
                            </div>
                        </div>
    
                        <!-- Pin dan Seal -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="apar_pin_seal_id" class="form-label required">Kondisi Pin dan Seal</label>
                                <select class="form-select @error('apar_pin_seal_id') is-invalid @enderror" 
                                        id="apar_pin_seal_id" name="apar_pin_seal_id" required>
                                    <option value="">Pilih Kondisi Pin & Seal</option>
                                    @foreach($pinSeals as $pinSeal)
                                        <option value="{{ $pinSeal->id }}" 
                                            {{ old('apar_pin_seal_id') == $pinSeal->id ? 'selected' : '' }}>
                                            {{ $pinSeal->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('apar_pin_seal_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Periksa pin pengaman dan segel APAR</div>
                            </div>
                            <div class="col-md-6">
                                <label for="apar_hose_id" class="form-label required">Kondisi Selang</label>
                                <select class="form-select @error('apar_hose_id') is-invalid @enderror" 
                                        id="apar_hose_id" name="apar_hose_id" required>
                                    <option value="">Pilih Kondisi Selang</option>
                                    @foreach($hoses as $hose)
                                        <option value="{{ $hose->id }}" 
                                            {{ old('apar_hose_id') == $hose->id ? 'selected' : '' }}>
                                            {{ $hose->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('apar_hose_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Periksa kondisi selang APAR</div>
                            </div>
                        </div>
    
                        <!-- Handle dan Kondisi Umum -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="apar_handle_id" class="form-label required">Kondisi Handle</label>
                                <select class="form-select @error('apar_handle_id') is-invalid @enderror" 
                                        id="apar_handle_id" name="apar_handle_id" required>
                                    <option value="">Pilih Kondisi Handle</option>
                                    @foreach($handles as $handle)
                                        <option value="{{ $handle->id }}" 
                                            {{ old('apar_handle_id') == $handle->id ? 'selected' : '' }}>
                                            {{ $handle->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('apar_handle_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Periksa kondisi handle/katup APAR</div>
                            </div>
                            <div class="col-md-6">
                                <label for="extinguisher_condition_id" class="form-label required">Kondisi Umum APAR</label>
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
                                <div class="form-text">Kondisi keseluruhan APAR</div>
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
                @endphp
                <input type="radio" class="btn-check" 
                       name="apar_pressure_id" 
                       id="pressure_{{ $pressure->id }}" 
                       value="{{ $pressure->id }}"
                       {{ old('apar_pressure_id') == $pressure->id ? 'checked' : '' }}
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
                @endphp
                <input type="radio" class="btn-check" 
                       name="apar_cylinder_id" 
                       id="cylinder_{{ $cylinder->id }}" 
                       value="{{ $cylinder->id }}"
                       {{ old('apar_cylinder_id') == $cylinder->id ? 'checked' : '' }}
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
                @endphp
                <input type="radio" class="btn-check" 
                       name="apar_pin_seal_id" 
                       id="pin_seal_{{ $pinSeal->id }}" 
                       value="{{ $pinSeal->id }}"
                       {{ old('apar_pin_seal_id') == $pinSeal->id ? 'checked' : '' }}
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
                @endphp
                <input type="radio" class="btn-check" 
                       name="apar_hose_id" 
                       id="hose_{{ $hose->id }}" 
                       value="{{ $hose->id }}"
                       {{ old('apar_hose_id') == $hose->id ? 'checked' : '' }}
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
                @endphp
                <input type="radio" class="btn-check" 
                       name="apar_handle_id" 
                       id="handle_{{ $handle->id }}" 
                       value="{{ $handle->id }}"
                       {{ old('apar_handle_id') == $handle->id ? 'checked' : '' }}
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
        <div class="form-text">Kondisi keseluruhan APAR</div>
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
                                    <a href="{{ route('apar-check.scan') }}" class="btn btn-sm btn-secondary">
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
                    <li>Tekanan dalam zona hijau</li>
                    <li>Tabung tidak penyok/berkarat</li>
                    <li>Pin pengaman masih utuh</li>
                    <li>Selang tidak retak/pecah</li>
                    <li>Handle mudah ditekan</li>
                    <li>Tidak ada kebocoran</li>
                    <li>Label masih terbaca</li>
                </ul>
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
                    {{-- <img src="{{ asset($apar->qr_code_url) }}" 
                         alt="QR Code {{ $apar->number_apar }}" 
                         class="img-fluid rounded" 
                         style="max-width: 200px;"> --}}
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
    // Form validation
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
        $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...');
    });

    // Real-time validation
    $('input[type="radio"]').on('change', function() {
        const name = $(this).attr('name');
        if ($(`input[name="${name}"]:checked`).length) {
            $(`input[name="${name}"]`).removeClass('is-invalid');
        }
    });

    // Auto-save draft (optional)
    let draftTimer;
    $('input, textarea').on('change input', function() {
        clearTimeout(draftTimer);
        draftTimer = setTimeout(saveDraft, 2000);
    });

    function saveDraft() {
        // Implement auto-save draft functionality here if needed
        console.log('Auto-saving draft...');
    }

    // Load saved draft on page load
    function loadDraft() {
        // Implement load draft functionality here if needed
        console.log('Loading draft...');
    }

    // Initialize
    loadDraft();
});
</script>
@endpush