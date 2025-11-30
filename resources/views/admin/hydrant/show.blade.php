@extends('admin.template')

@section('title', 'Detail data Hydrant')

@section('breadcrumb')
    <h1 class="mt-4">
        Detail Hydrant</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('hydrant.index') }}" class="text-decoration-none text-secondary">List</a>
        </li>
        <li class="breadcrumb-item">Detail</li>
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
                    <div class="table-responsive">
                        <table class="table border mb-0 align-middle bg-white">
                            <tr>
                                <th>Nomor Hydrant</th>
                                <td>{{ $hydrant->number_hydrant }}</td>
                            </tr>
                            <tr>
                                <th>Lokasi</th>
                                <td>{{ $hydrant->location }}</td>
                            </tr>
                            <tr>
                                <th>Zona</th>
                                <td>{{ $hydrant->zone->name }}</td>
                            </tr>
                            <tr>
                                <th>Gedung</th>
                                <td>{{ $hydrant->building->name }}</td>
                            </tr>
                            <tr>
                                <th>Lantai</th>
                                <td>{{ $hydrant->floor->name }}</td>
                            </tr>
                            <tr>
                                <th>Keterangan</th>
                                <td>{{ $hydrant->description }}</td>
                            </tr>
                            <tr>
                                <th>Merek</th>
                                <td>{{ $hydrant->brand->name }}</td>
                            </tr>
                            <tr>
                                <th>Tipe Hydrant</th>
                                <td>{{ $hydrant->hydrantType->name }}</td>
                            </tr>
                            <tr>
                                <th>Kondisi Hydrant</th>
                                {{-- <td>{{ $hydrant->extinguisherCondition->name }}</td> --}}
                                <td>
                                    @if ($hydrant->extinguisher_condition_badge)
                                    {!! $hydrant->extinguisher_condition_badge !!}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>{!! $hydrant->status_badge !!}</td>
                            </tr>
                            <tr>
                                <th>Barcode Hydrant</th>
                                <td>
                                    @if ($hydrant->barcode)
                                        <div style="max-width: 200px;">
                                            {!! $hydrant->barcode_image !!}
                                            <small class="d-block">{{ $hydrant->barcode }}</small>
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>QR Code</th>
                                <td>
                                    @if ($hydrant->qr_code)
                                        <div class="d-grid" style="max-width: 100px;">
                                            {{-- <div style="width: 100px; height: 100px; margin: 0 auto;">
                          {!! $hydrant->qr_code_svg !!}
                      </div> --}}
                                            {!! $hydrant->qr_code_svg !!}
                                            {{-- <small class="text-muted">{{ Str::limit($hydrant->qr_code, 10) }}</small> --}}
                                            <small class="d-block text-muted">{{ $hydrant->qr_code }}</small>
                                            <!-- Download Button -->
                                            <a href="{{ route('hydrant.download-qrcode-svg', $hydrant->id) }}"
                                                class="btn btn-sm btn-light" style="border: 1px solid rgba(0,0,0,0.15)" title="Download QR Code PNG">
                                                <i class="fa-solid fa-download fa-xs"></i> Unduh
                                            </a>
                                        </div>
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
@endsection
