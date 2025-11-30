@extends('admin.template')

@section('title', 'Detail Pengecekan APAR')

@section('breadcrumb')
    <h1 class="mt-4">Detail Pengecekan APAR</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('apar-check.index') }}" class="text-decoration-none text-secondary">List
                Pengecekan</a></li>
        <li class="breadcrumb-item ">Detail</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-clipboard-check me-2"></i>Detail Hasil Pengecekan APAR</h5>
                        <a href="{{ route('apar-check.index') }}" class="btn btn-light btn-sm">
                            <i class="fa-solid fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body p-1 bg-light">
                    <div class="form-input">
                        <!-- Informasi APAR -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-fire-extinguisher me-2"></i>Informasi APAR</h6>
                                    </div>
                                    <div class="card-body p-1 bg-light">
                                        <div class="form-input">
                                            <div class="table-responsive">
                                                <table class="table border mb-0 align-middle">
                                                    <tr>
                                                        <th style="width: 40%">Nomor APAR</th>
                                                        <td>{{ $aparCheck->apar->number_apar }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Lokasi</th>
                                                        <td>{{ $aparCheck->apar->location }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Zona</th>
                                                        <td>{{ $aparCheck->apar->zone->name }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Gedung</th>
                                                        <td>{{ $aparCheck->apar->building->name }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Lantai</th>
                                                        <td>{{ $aparCheck->apar->floor->name }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Merek</th>
                                                        <td>{{ $aparCheck->apar->brand->name }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Tipe APAR</th>
                                                        <td>{{ $aparCheck->apar->aparType->name }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Pengecekan</h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table border mb-0 align-middle">
                                                <tr>
                                                    <th style="width: 40%">Tanggal Pengecekan</th>
                                                    <td>{{ $aparCheck->formatted_check_date }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Pemeriksa</th>
                                                    <td>{{ $aparCheck->user->name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Status</th>
                                                    <td>{!! $aparCheck->status_badge !!}</td>
                                                </tr>
                                                <tr>
                                                    <th>Waktu Pengecekan</th>
                                                    <td>{{ $aparCheck->created_at->format('d M Y H:i') }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Hasil Pengecekan -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-tasks me-2"></i>Hasil Pengecekan</h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table border mb-0 align-middle">
                                                <tr>
                                                    <th style="width: 30%">Kondisi Tekanan</th>
                                                    <td>
                                                        @if ($aparCheck->pressure)
                                                            <span
                                                                class="badge bg-{{ $aparCheck->pressure->name !== 'baik' ? 'success' : 'danger' }}">
                                                                {{ $aparCheck->pressure->name }}
                                                            </span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Kondisi Silinder</th>
                                                    <td>
                                                        @if ($aparCheck->cylinder)
                                                            <span
                                                                class="badge bg-{{ $aparCheck->cylinder->name != 'Baik' ? 'success' : 'warning' }}">
                                                                {{ $aparCheck->cylinder->name }}
                                                            </span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Kondisi Pin dan Seal</th>
                                                    <td>
                                                        @if ($aparCheck->pinSeal)
                                                            <span
                                                                class="badge bg-{{ $aparCheck->pinSeal->name !== 'utuh' ? 'success' : 'danger' }}">
                                                                {{ $aparCheck->pinSeal->name }}
                                                            </span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Kondisi Selang</th>
                                                    <td>
                                                        @if ($aparCheck->hose)
                                                            <span
                                                                class="badge bg-{{ $aparCheck->hose->name !== 'baik' ? 'success' : 'danger' }}">
                                                                {{ $aparCheck->hose->name }}
                                                            </span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Kondisi Handle</th>
                                                    <td>
                                                        @if ($aparCheck->handle)
                                                            <span
                                                                class="badge bg-{{ $aparCheck->handle->name !== 'baik' ? 'success' : 'danger' }}">
                                                                {{ $aparCheck->handle->name }}
                                                            </span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Kondisi Umum APAR</th>
                                                    <td>
                                                        @if ($aparCheck->extinguisherCondition)
                                                            <span
                                                                class="badge bg-{{ $aparCheck->extinguisherCondition->name !== 'normal' ? 'success' : 'warning' }}">
                                                                {{ $aparCheck->extinguisherCondition->name }}
                                                            </span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Catatan Tambahan -->
                        @if ($aparCheck->notes)
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0"><i class="fas fa-sticky-note me-2"></i>Catatan Tambahan</h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="mb-0">{{ $aparCheck->additional_notes }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Foto Bukti -->
                        @if ($aparCheck->photo_evidence)
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0"><i class="fas fa-camera me-2"></i>Foto Bukti</h6>
                                        </div>
                                        <div class="card-body text-center">
                                            <img src="{{ asset('storage/' . $aparCheck->photo_evidence) }}"
                                                alt="Foto Bukti Pengecekan" class="img-fluid rounded"
                                                style="max-height: 400px;">
                                            <div class="mt-2">
                                                <small class="text-muted">Foto diambil pada:
                                                    {{ $aparCheck->created_at->format('d M Y H:i') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Tanda Tangan -->
                        @if ($aparCheck->signature)
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0"><i class="fas fa-signature me-2"></i>Tanda Tangan Pemeriksa
                                            </h6>
                                        </div>
                                        <div class="card-body text-center">
                                            <img src="{{ asset('storage/' . $aparCheck->signature) }}"
                                                alt="Tanda Tangan Pemeriksa" class="img-fluid"
                                                style="max-height: 100px; background: white; padding: 10px; border: 1px solid #dee2e6;">
                                            <div class="mt-2">
                                                <p class="mb-1"><strong>{{ $aparCheck->user->name }}</strong></p>
                                                <small class="text-muted">Pemeriksa</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            Terakhir diperbarui: {{ $aparCheck->updated_at->format('d M Y H:i') }}
                        </small>
                        <div>
                            @if (auth()->user()->can('edit_apar_check') || auth()->user()->id === $aparCheck->user_id)
                                <a href="{{ route('apar-check.edit', $aparCheck->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </a>
                            @endif

                            @if (auth()->user()->can('delete_apar_check'))
                                <form action="{{ route('apar-check.destroy', $aparCheck->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus data pengecekan ini?')">
                                        <i class="fas fa-trash me-1"></i> Hapus
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        .card-header h6 {
            font-weight: 600;
        }
    </style>
@endpush
