@extends('admin.template')

@section('title', 'Detail data APAR')

@section('breadcrumb')
    <h1 class="mt-4">
        Detail APAR</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('apar.index') }}" class="text-decoration-none text-secondary">List</a>
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
                        <a href="{{ route('apar.index') }}" class="text-decoration-none text-dark"><i
                                class="fa-solid fa-arrow-left"></i> Back</a>
                    </div>
                </div>
                <div class="card-body p-1 bg-light">
                    <div class="table-responsive">
                        <table class="table border mb-0 align-middle bg-white">
                            <tr>
                                <th>Nomor APAR</th>
                                <td>{{ $apar->number_apar }}</td>
                            </tr>
                            <tr>
                                <th>Berat APAR</th>
                                <td>{{ $apar->formatted_weight }}</td>
                            </tr>
                            <tr>
                                <th>Lokasi</th>
                                <td>{{ $apar->location }}</td>
                            </tr>
                            <tr>
                                <th>Zona</th>
                                <td>{{ $apar->zone->name }}</td>
                            </tr>
                            <tr>
                                <th>Gedung</th>
                                <td>{{ $apar->building->name }}</td>
                            </tr>
                            <tr>
                                <th>Lantai</th>
                                <td>{{ $apar->floor->name }}</td>
                            </tr>
                            <tr>
                                <th>Keterangan</th>
                                <td>{{ $apar->description }}</td>
                            </tr>
                            <tr>
                                <th>Merek</th>
                                <td>{{ $apar->brand->name }}</td>
                            </tr>
                            <tr>
                                <th>Tipe APAR</th>
                                <td>{{ $apar->aparType->name }}</td>
                            </tr>
                            <tr>
                                <th>Kondisi APAR</th>
                                {{-- <td>{{ $apar->extinguisherCondition->name }}</td> --}}
                                <td>
                                    @if ($apar->extinguisher_condition_badge)
                                    {!! $apar->extinguisher_condition_badge !!}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Expired Date</th>
                                <td>
                                    @if ($apar->formatted_expired_date)
                                        {{ $apar->formatted_expired_date }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>{!! $apar->status_badge !!}</td>
                            </tr>
                            <tr>
                                <th>Barcode APAR</th>
                                <td>
                                    @if ($apar->barcode)
                                        <div style="max-width: 200px;">
                                            {!! $apar->barcode_image !!}
                                            <small class="d-block">{{ $apar->barcode }}</small>
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>QR Code</th>
                                <td>
                                    @if ($apar->qr_code)
                                        <div class="d-grid" style="max-width: 100px;">
                                            {{-- <div style="width: 100px; height: 100px; margin: 0 auto;">
                          {!! $apar->qr_code_svg !!}
                      </div> --}}
                                            {!! $apar->qr_code_svg !!}
                                            {{-- <small class="text-muted">{{ Str::limit($apar->qr_code, 10) }}</small> --}}
                                            <small class="d-block text-muted">{{ $apar->qr_code }}</small>
                                            <!-- Download Button -->
                                            <a href="{{ route('apar.download-qrcode-svg', $apar->id) }}"
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
