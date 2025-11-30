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
            <!-- Result Display -->
            <div id="validation-result" class="mt-1 mb-3" style="display: none;">
                <!-- Hasil validasi akan ditampilkan di sini -->
            </div>

            <div class="card">
                <div class="card-header">
                    <i class="fas fa-qrcode me-2"></i>
                    Scan QR Code APAR
                </div>
                <div class="card-body text-center p-3">
                    <!-- QR Code Scanner Area -->
                    <div class="scanner-section" id="scanner-section">
                        <div id="scanner-status" class="mb-2"></div>
                        <div id="preview-container" class="text-center mb-3">
                            <video id="preview" class="img-fluid rounded"
                                style="max-width: 100%; height: 300px; background: #000;"></video>
                        </div>
                        <div class="text-center mb-3">
                            <button id="start-scanner" class="btn btn-primary btn-sm">
                                <i class="fas fa-camera"></i> Start Scanner
                            </button>
                            <button id="stop-scanner" class="btn btn-warning btn-sm" style="display: none;">
                                <i class="fas fa-stop"></i> Stop Scanner
                            </button>
                        </div>
                    </div>

                    <!-- Manual Input - Tampil langsung di bawah scanner -->
                    <div class="manual-input-section border-top pt-3">
                        <h6 class="text-center text-muted mb-3">
                            <i class="fas fa-keyboard me-2"></i>Atau Input Manual
                        </h6>
                        <form id="manual-form">
                            @csrf
                            <div class="row g-2 align-items-end">
                                <div class="col-md-8">
                                    <div class="mb-2">
                                        <label for="qr_code" class="form-label small mb-1">Kode/Nomor APAR</label>
                                        <input type="text" class="form-control form-control-sm" id="qr_code" name="qr_code"
                                            placeholder="Masukkan kode APAR" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary btn-sm w-100 mb-2">
                                        <i class="fas fa-search"></i> Cari
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div class="text-center">
                            <small class="text-muted">Scanner otomatis mendeteksi QR code</small>
                        </div>
                    </div>

                    <div id="validation-result" style="display: none;"></div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-info-circle me-2"></i>
                    Informasi Pengecekan
                </div>
                <div class="card-body p-3">
                    <h6>Langkah-langkah:</h6>
                    <ol class="small">
                        <li>Scanner kamera akan otomatis aktif</li>
                        <li>Arahkan kamera ke QR code pada APAR</li>
                        <li>Scanner akan otomatis mendeteksi QR code</li>
                        <li>Atau input manual nomor APAR di form bawah</li>
                        <li>Sistem akan validasi APAR dan akses Anda</li>
                        <li>Isi form pengecekan APAR</li>
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

                    <div class="alert alert-info mt-3">
                        <small>
                            <i class="fas fa-lightbulb me-2"></i>
                            <strong>Tips:</strong> Pastikan pencahayaan cukup dan QR code tidak rusak untuk scanning optimal.
                        </small>
                    </div>
                </div>
            </div>

            <!-- Result Display -->
            <div id="validation-result-right" class="mt-3" style="display: none;">
                <!-- Hasil validasi akan ditampilkan di sini -->
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .scanner-controls {
            display: flex;
            gap: 8px;
            justify-content: center;
            align-items: center;
            flex-wrap: wrap;
        }

        .scanner-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            color: #6c757d;
            height: 100%;
            padding: 20px;
            text-align: center;
        }

        .scanner-placeholder i {
            font-size: 48px;
            margin-bottom: 10px;
            color: #adb5bd;
        }

        .scanner-loading {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            color: #6c757d;
            height: 100%;
            padding: 20px;
        }

        .manual-input-section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
        }
    </style>
@endpush

@push('scripts')
    <!-- JsQR Library -->
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>

    <script>
        $(document).ready(function() {
            let videoStream = null;
            let isScanning = false;
            let animationFrame = null;

            // Manual form submission
            $('#manual-form').on('submit', function(e) {
                e.preventDefault();
                const qrCode = $('#qr_code').val().trim();
                if (qrCode) {
                    validateApar(qrCode);
                }
            });

            // QR Scanner functionality - Auto start
            $('#start-scanner').on('click', function() {
                startJsQRScanner();
            });

            $('#stop-scanner').on('click', function() {
                stopJsQRScanner();
            });

            async function startJsQRScanner() {
                if (isScanning) return;

                try {
                    console.log('Memulai akses kamera...');

                    // Constraint sederhana untuk kompatibilitas maksimal
                    const constraints = {
                        video: {
                            facingMode: { ideal: "environment" },
                            width: { min: 320, ideal: 640, max: 1280 },
                            height: { min: 240, ideal: 480, max: 720 },
                            frameRate: { ideal: 15, max: 30 }
                        },
                        audio: false
                    };

                    // Timeout handling
                    const timeoutPromise = new Promise((_, reject) => {
                        setTimeout(() => reject(new Error('Camera access timeout')), 10000);
                    });

                    const videoPromise = navigator.mediaDevices.getUserMedia(constraints);
                    videoStream = await Promise.race([videoPromise, timeoutPromise]);

                    const video = document.getElementById('preview');
                    video.srcObject = videoStream;
                    video.setAttribute("playsinline", true);

                    // Handle video play
                    try {
                        await video.play();
                    } catch (playError) {
                        console.warn('Video play error:', playError);
                    }

                    // Setup QR scanning
                    const canvas = document.createElement('canvas');
                    const canvasContext = canvas.getContext('2d');

                    isScanning = true;
                    $('#start-scanner').hide();
                    $('#stop-scanner').show();
                    $('#scanner-status').html(
                        '<div class="alert alert-success py-2"><small><i class="fas fa-camera"></i> Scanner aktif - Arahkan kamera ke QR code</small></div>'
                    );

                    // Scanning loop
                    function scanFrame() {
                        if (!isScanning) return;

                        try {
                            if (video.readyState === video.HAVE_ENOUGH_DATA && video.videoWidth > 0) {
                                canvas.width = video.videoWidth;
                                canvas.height = video.videoHeight;
                                canvasContext.drawImage(video, 0, 0, canvas.width, canvas.height);

                                const imageData = canvasContext.getImageData(0, 0, canvas.width, canvas.height);
                                const code = jsQR(imageData.data, imageData.width, imageData.height, {
                                    inversionAttempts: "dontInvert",
                                });

                                if (code) {
                                    console.log('QR Code ditemukan:', code.data);
                                    stopJsQRScanner();
                                    validateApar(code.data);
                                    return;
                                }
                            }
                        } catch (error) {
                            console.error('Error dalam scan frame:', error);
                        }

                        animationFrame = requestAnimationFrame(scanFrame);
                    }

                    scanFrame();

                } catch (error) {
                    console.error("Gagal memulai scanner:", error);
                    handleScannerError(error);
                }
            }

            function stopJsQRScanner() {
                isScanning = false;

                if (animationFrame) {
                    cancelAnimationFrame(animationFrame);
                    animationFrame = null;
                }

                if (videoStream) {
                    videoStream.getTracks().forEach(track => {
                        track.stop();
                    });
                    videoStream = null;
                }

                $('#start-scanner').show();
                $('#stop-scanner').hide();
                $('#scanner-status').html('');

                const video = document.getElementById('preview');
                if (video) {
                    video.srcObject = null;
                }
            }

            function handleScannerError(error) {
                console.error('Scanner Error:', error);

                let errorMessage = '';
                let errorType = 'warning';

                if (error.name === 'NotAllowedError' || error.name === 'PermissionDeniedError') {
                    errorMessage = `
                        <small><i class="fas fa-ban"></i> Izin kamera ditolak. Gunakan input manual di bawah.</small>
                    `;
                } else if (error.name === 'NotFoundError' || error.name === 'DevicesNotFoundError') {
                    errorMessage = `
                        <small><i class="fas fa-camera-slash"></i> Kamera tidak ditemukan. Gunakan input manual.</small>
                    `;
                } else if (error.message === 'Camera access timeout') {
                    errorMessage = `
                        <small><i class="fas fa-clock"></i> Kamera timeout. Gunakan input manual di bawah.</small>
                    `;
                } else {
                    errorMessage = `
                        <small><i class="fas fa-exclamation-triangle"></i> Gagal akses kamera. Gunakan input manual.</small>
                    `;
                }

                $('#scanner-status').html(`
                    <div class="alert alert-${errorType} py-2">
                        ${errorMessage}
                    </div>
                `);

                // Tetap tampilkan form manual (sudah visible)
            }

            function validateApar(numberApar) {
                // Show loading di kedua tempat
                $('#validation-result').html(`
                    <div class="alert alert-info">
                        <i class="fas fa-spinner fa-spin"></i> Memvalidasi APAR: <strong>${numberApar}</strong>
                    </div>
                `).show();

                $('#validation-result-right').html(`
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
                            $('#validation-result').html(`
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle"></i> Validasi Berhasil! Mengalihkan...
                                </div>
                            `).show();

                            $('#validation-result-right').html(`
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle"></i> APAR valid! Mengalihkan ke form pengecekan...
                                </div>
                            `).show();

                            // Redirect setelah delay singkat
                            setTimeout(() => {
                                window.location.href = response.data.redirect_url;
                            }, 1000);

                        } else {
                            $('#validation-result').html(`
                                <div class="alert alert-danger">
                                    <h6><i class="fas fa-exclamation-triangle"></i> Gagal Validasi</h6>
                                    <p>${response.message}</p>
                                    <button onclick="restartScanner()" class="btn btn-warning btn-sm mt-2">
                                        <i class="fas fa-redo"></i> Coba Lagi
                                    </button>
                                </div>
                            `).show();

                            $('#validation-result-right').html(`
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
                                <button onclick="restartScanner()" class="btn btn-warning btn-sm mt-2">
                                    <i class="fas fa-redo"></i> Coba Lagi
                                </button>
                            </div>
                        `).show();

                        $('#validation-result-right').html(`
                            <div class="alert alert-danger">
                                <h6><i class="fas fa-exclamation-triangle"></i> Error Validasi</h6>
                                <p>${message}</p>
                            </div>
                        `).show();
                    }
                });
            }

            // Global function untuk restart scanner
            window.restartScanner = function() {
                $('#validation-result').hide();
                $('#validation-result-right').hide();
                stopJsQRScanner();
                setTimeout(() => {
                    startJsQRScanner();
                }, 500);
            };

            // Auto start scanner ketika halaman dimuat
            setTimeout(() => {
                startJsQRScanner();
            }, 500);

            // Cleanup ketika meninggalkan halaman
            $(window).on('beforeunload', function() {
                stopJsQRScanner();
            });
        });
    </script>
@endpush