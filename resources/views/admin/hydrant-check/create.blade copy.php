@extends('admin.template')

@section('title', 'Pengecekan Hydrant - ' . $hydrant->number_hydrant)

@section('breadcrumb')
    <h1 class="mt-4">Pengecekan Hydrant</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('master.index') }}" class="text-decoration-none text-secondary">Master</a></li>
        <li class="breadcrumb-item"><a href="{{ route('hydrant-check.scan') }}" class="text-decoration-none text-secondary">Scan Hydrant</a></li>
        <li class="breadcrumb-item active">Pengecekan {{ $hydrant->number_hydrant }}</li>
    </ol>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-clipboard-check me-2"></i>
                Form Pengecekan Hydrant
            </div>
            <div class="card-body">
                <form action="{{ route('hydrant-check.store', $hydrant->id) }}" method="POST" id="checkForm">
                    @csrf

                    <!-- Informasi Hydrant -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="alert alert-info">
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
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kondisi Pintu -->
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
                    </div>

                    <!-- Action Buttons -->
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('hydrant-check.scan') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Kembali
                                </a>
                                <div class="d-flex gap-2">
                                    <button type="reset" class="btn btn-outline-secondary">
                                        <i class="fas fa-undo me-1"></i> Reset
                                    </button>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
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

    <div class="col-md-4">
        <!-- Informasi Pemeriksa -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <i class="fas fa-user me-2"></i>
                Informasi Pemeriksa
            </div>
            <div class="card-body">
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

        <!-- Panduan Pengecekan -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <i class="fas fa-lightbulb me-2"></i>
                Panduan Pengecekan
            </div>
            <div class="card-body small">
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

        <!-- QR Code Hydrant -->
        <div class="card">
            <div class="card-header bg-success text-white">
                <i class="fas fa-qrcode me-2"></i>
                QR Code Hydrant
            </div>
            <div class="card-body text-center">
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
            const element = $(`#${field}`);
            if (!element.val()) {
                isValid = false;
                element.addClass('is-invalid');
                if (!firstInvalidField) {
                    firstInvalidField = element;
                }
            } else {
                element.removeClass('is-invalid');
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

    // Real-time validation
    $('select[required]').on('change', function() {
        if ($(this).val()) {
            $(this).removeClass('is-invalid');
        }
    });

    // Auto-save draft (optional)
    let draftTimer;
    $('select, textarea').on('change input', function() {
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