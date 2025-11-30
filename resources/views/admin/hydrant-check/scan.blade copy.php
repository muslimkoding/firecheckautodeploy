

@extends('admin.template')

@section('title', 'Scan APAR untuk Pengecekan')

@section('breadcrumb')
    <h1 class="mt-4">Pengecekan APAR</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('master.index') }}"
                class="text-decoration-none text-secondary">Master</a></li>
        <li class="breadcrumb-item active">Scan APAR</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-qrcode me-2"></i>
                    Scan QR Code APAR
                </div>
                <div class="card-body text-center">
                    <!-- QR Code Scanner Area -->
                    <div id="qr-scanner" class="mb-3">
                        <div id="reader" style="width: 100%; height: 300px;"></div>
                        <button id="start-scanner" class="btn btn-success btn-sm mt-2">
                            <i class="fas fa-camera"></i> Mulai Scanner
                        </button>
                        <button id="stop-scanner" class="btn btn-danger btn-sm mt-2" style="display: none;">
                            <i class="fas fa-stop"></i> Stop Scanner
                        </button>
                    </div>

                    <div class="text-muted mb-3">
                        <small>Scan QR code pada APAR untuk memulai pengecekan</small>
                    </div>

                    <!-- Manual Input -->
                    <div class="border-top pt-3">
                        <h6 class="text-center">Atau Input Manual</h6>
                        <form id="manual-form">
                            @csrf
                            <div class="mb-3">
                                <label for="qr_code" class="form-label">Nomor APAR</label>
                                <input type="text" class="form-control" id="qr_code" name="qr_code"
                                    placeholder="Masukkan nomor APAR" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search"></i> Cari APAR
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <i class="fas fa-info-circle me-2"></i>
                    Informasi Pengecekan
                </div>
                <div class="card-body">
                    <h6>Langkah-langkah:</h6>
                    <ol class="small">
                        <li>Scan QR code pada APAR menggunakan kamera</li>
                        <li>Atau input manual nomor APAR</li>
                        <li>Sistem akan validasi APAR dan akses Anda</li>
                        <li>Isi form pengecekan APAR</li>
                        <li>Submit hasil pengecekan</li>
                    </ol>

                    <h6 class="mt-3">Yang akan dicek:</h6>
                    <ul class="small">
                        <li>Kondisi tekanan</li>
                        <li>Kondisi silinder</li>
                        <li>Pin dan seal</li>
                        <li>Selang</li>
                        <li>Handle</li>
                        <li>Kondisi umum APAR</li>
                        <li>Catatan tambahan</li>
                    </ul>
                </div>
            </div>

            <!-- Result Display -->
            <div id="validation-result" class="mt-3" style="display: none;">
                <!-- Hasil validasi akan ditampilkan di sini -->
            </div>
        </div>
    </div>
@endsection

@push('scripts')
   
    <script src="{{ asset('assets/html5-qrcode.min.js') }}"></script>





    <script>
        $(document).ready(function() {
            let html5QrcodeScanner = null;

            // Manual form submission
            $('#manual-form').on('submit', function(e) {
                e.preventDefault();
                validateApar($('#qr_code').val());
            });

            // QR Scanner functionality
            $('#start-scanner').on('click', function() {
                startScanner();
            });

            $('#stop-scanner').on('click', function() {
                stopScanner();
            });

            function startScanner() {

                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                    alert("Browser Anda tidak mendukung kamera.");
                    return;
                }

                try {
                    html5QrcodeScanner = new Html5QrcodeScanner(
                        "reader", {
                            fps: 10,
                            qrbox: {
                                width: 120,
                                height: 120
                                // width: 250,
                                // height: 250
                            }
                        },
                        false
                    );

                    html5QrcodeScanner.render(onScanSuccess, onScanFailure);

                    $('#start-scanner').hide();
                    $('#stop-scanner').show();

                } catch (err) {
                    console.error("Gagal memulai scanner:", err);
                    alert("Scanner tidak dapat dimulai.");
                }
            }


            function stopScanner() {
                if (html5QrcodeScanner) {
                    html5QrcodeScanner.clear().then(() => {
                        $('#start-scanner').show();
                        $('#stop-scanner').hide();
                    }).catch(err => {
                        console.error("Failed to clear scanner", err);
                    });
                }
            }

            function onScanSuccess(decodedText, decodedResult) {
                // Stop scanner setelah berhasil scan
                stopScanner();

                // Validasi APAR dari QR code content
                validateApar(decodedText);
            }

            function onScanFailure(error) {
                // Handle scan failure, if needed
                console.warn(`QR scan failed: ${error}`);
            }

            function validateApar(numberApar) {
                // Show loading
                $('#validation-result').html(`
            <div class="alert alert-info">
                <i class="fas fa-spinner fa-spin"></i> Memvalidasi APAR...
            </div>
        `).show();

                $.ajax({
                    url: "{{ route('apar-check.validate') }}",
                    method: 'POST',
                    data: {
                        qr_code: numberApar,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.success) {
                            // Hapus semua HTML alert, langsung redirect!
                            window.location.href = response.data.redirect_url;

                            // Tampilkan alert success sementara sebelum redirect
                            $('#validation-result').html(`
                <div class="alert alert-success">
                    <i class="fas fa-check-circle fa-spin"></i> Validasi Berhasil! Mengalihkan ke form pengecekan...
                </div>
            `).show();

                            // Catatan: Redirect di atas akan terjadi hampir instan, sehingga alert ini hanya muncul sesaat.

                        } else {
                            // Gagal validasi (error APAR tidak aktif/tidak ditemukan)
                            $('#validation-result').html(`
                <div class="alert alert-danger">
                    <h6><i class="fas fa-exclamation-triangle"></i> Gagal Validasi</h6>
                    <p>${response.message}</p>
                </div>
            `).show();
                        }
                    },
                    error: function(xhr) {
                        let message = 'Terjadi kesalahan saat validasi APAR';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }

                        $('#validation-result').html(`
                    <div class="alert alert-danger">
                        <h6><i class="fas fa-exclamation-triangle"></i> Error</h6>
                        <p>${message}</p>
                    </div>
                `);
                    }
                });
            }
        });
    </script>
@endpush
