@extends('admin.template')

@section('title', 'Detail Pengecekan Hydrant')

@section('breadcrumb')
    <h1 class="mt-4">Detail Pengecekan Hydrant</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('hydrant-check.index') }}" class="text-decoration-none text-secondary">List
                Pengecekan</a></li>
        <li class="breadcrumb-item ">Detail</li>
    </ol>
@endsection

@section('content')

    <div class="row">
        <!-- Informasi Hydrant -->
        <div class="row mb-4">
            <div class="col-md-6 mb-4 mb-md-0">
                <div class="card">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-fire-extinguisher me-2"></i>Informasi Hydrant</h6>
                    </div>
                    <div class="card-body p-1 bg-light">
                        <div class="table-responsive">
                            <table class="table border mb-0 align-middle bg-white">
                                <tr>
                                    <th class="bg-white" style="width: 40%">Nomor Hydrant</th>
                                    <td>{{ $hydrantCheck->hydrant->number_hydrant }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-white">Lokasi</th>
                                    <td>{{ $hydrantCheck->hydrant->location }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-white">Zona</th>
                                    <td>{{ $hydrantCheck->hydrant->zone->name }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-white">Gedung</th>
                                    <td>{{ $hydrantCheck->hydrant->building->name }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-white">Lantai</th>
                                    <td>{{ $hydrantCheck->hydrant->floor->name }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-white">Merek</th>
                                    <td>{{ $hydrantCheck->hydrant->brand->name }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-white">Tipe Hydrant</th>
                                    <td>{{ $hydrantCheck->hydrant->hydrantType->name }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Pengecekan</h6>
                    </div>
                    <div class="card-body p-1 bg-light">
                        <div class="table-responsive">
                            <table class="table border mb-0 align-middle bg-white">
                                <tr>
                                    <th class="bg-white" style="width: 40%">Tanggal Pengecekan</th>
                                    <td>{{ $hydrantCheck->formatted_check_date }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-white">Pemeriksa</th>
                                    <td>{{ $hydrantCheck->user->name }}</td>
                                </tr>
                                <tr>
                                    <th class="bg-white">Status</th>
                                    <td>{!! $hydrantCheck->status_badge !!}</td>
                                </tr>
                                <tr>
                                    <th class="bg-white">Waktu Pengecekan</th>
                                    <td>{{ $hydrantCheck->created_at->format('d M Y H:i') }}</td>
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
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-tasks me-2"></i>Hasil Pengecekan</h6>
                    </div>
                    <div class="card-body p-1 bg-light">
                        <div class="table-responsive bg-white">
                            <table class="table border mb-0 align-middle bg-white">
                                <tr>
                                    <th style="width: 30%"  class="bg-white">Kondisi Pintu Box</th>
                                    <td>
                                        @if ($hydrantCheck->hydrantDoor)
                                            <span
                                                class="badge bg-{{ $hydrantCheck->hydrantDoor->name == 'Terbuka' ? 'success' : 'danger' }}">
                                                {{ $hydrantCheck->hydrantDoor->name }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-white">Kondisi Coupling</th>
                                    <td>
                                        @if ($hydrantCheck->hydrantCoupling)
                                            <span
                                                class="badge bg-{{ $hydrantCheck->hydrantCoupling->name == 'Baik' ? 'success' : 'warning' }}">
                                                {{ $hydrantCheck->hydrantCoupling->name }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-white">Kondisi Main Valve</th>
                                    <td>
                                        @if ($hydrantCheck->hydrantMainValve)
                                            <span
                                                class="badge bg-{{ $hydrantCheck->hydrantMainValve->name == 'Baik' ? 'success' : 'danger' }}">
                                                {{ $hydrantCheck->hydrantMainValve->name }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-white">Kondisi Selang</th>
                                    <td>
                                        @if ($hydrantCheck->hydrantHose)
                                            <span
                                                class="badge bg-{{ $hydrantCheck->hydrantHose->name == 'Baik' ? 'success' : 'danger' }}">
                                                {{ $hydrantCheck->hydrantHose->name }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-white">Kondisi Nozzle</th>
                                    <td>
                                        @if ($hydrantCheck->hydrantNozzle)
                                            <span
                                                class="badge bg-{{ $hydrantCheck->hydrantNozzle->name == 'Baik' ? 'success' : 'danger' }}">
                                                {{ $hydrantCheck->hydrantNozzle->name }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-white">Ketersediaan Safety Marking</th>
                                    <td>
                                        @if ($hydrantCheck->hydrantSafetyMarking)
                                            <span
                                                class="badge bg-{{ $hydrantCheck->hydrantSafetyMarking->name == 'Baik' ? 'success' : 'danger' }}">
                                                {{ $hydrantCheck->hydrantSafetyMarking->name }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-white">Ketersediaan Petunjuk Penggunaan</th>
                                    <td>
                                        @if ($hydrantCheck->hydrantGuide)
                                            <span
                                                class="badge bg-{{ $hydrantCheck->hydrantGuide->name == 'Tersedia' ? 'success' : 'danger' }}">
                                                {{ $hydrantCheck->hydrantGuide->name }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-white">Kondisi Umum</th>
                                    <td>
                                        @if ($hydrantCheck->condition)
                                            <span
                                                class="badge bg-{{ $hydrantCheck->condition->name == 'Normal' ? 'success' : 'danger' }}">
                                                {{ $hydrantCheck->condition->name }}
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
        @if ($hydrantCheck->notes)
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-sticky-note me-2"></i>Catatan Tambahan</h6>
                        </div>
                        <div class="card-body p-1 bg-light">
                            <div class="form-input">
                            <p class="mb-0">Catatan : {{ $hydrantCheck->notes }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="row mt-4 mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                Terakhir diperbarui: {{ $hydrantCheck->updated_at->format('d M Y H:i') }}
                            </small>
                            <div>
                                @if (auth()->user()->can('edit_hydrant_check') || auth()->user()->id === $hydrantCheck->user_id)
                                    <a href="{{ route('hydrant-check.edit', $hydrantCheck->id) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit me-1"></i> Edit
                                    </a>
                                @endif
    
                                @if (auth()->user()->can('delete_hydrant_check'))
                                    <form action="{{ route('hydrant-check.destroy', $hydrantCheck->id) }}" method="POST"
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
