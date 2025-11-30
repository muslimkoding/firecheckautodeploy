@extends('admin.template')

@section('title', 'Scan Hydrant untuk Pengecekan')

@section('breadcrumb')
    <h1 class="mt-4">Pengecekan Hydrant</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('hydrant-check.to-check') }}"
            class="text-decoration-none text-secondary">Checklist</a></li>
        <li class="breadcrumb-item ">Scan Hydrant</li>
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
                    Scan QR Code Hydrant
                </div>
                <div class="card-body text-center p-1 bg-light">
                    <div class="form-input">
                        <!-- QR Code Scanner Area -->
                        <div class="scanner-section" id="scanner-section">
                            <div id="scanner-status"></div>
                            <div id="preview-container" class="text-center">
                                <video id="preview" class="img-fluid "
                                    style="max-width: 100%; width: 300px; border-radius: 25px; background: #e1e1e1;"></video>
                            </div>
                            <div class="text-center mt-3">
                                <button id="start-scanner" class="btn btn-primary">
                                    <i class="fas fa-camera"></i> Start Scanner
                                </button>
                                <button id="stop-scanner" class="btn btn-warning" style="display: none;">
                                    <i class="fas fa-stop"></i> Stop Scanner
                                </button>
                                {{-- <button onclick="switchToManualInput()" class="btn btn-outline-secondary">
                                    <i class="fas fa-keyboard"></i> Input Manual
                                </button> --}}
                            </div>
                        </div>

                        {{-- <small class="text-muted mt-1 d-block">
                            <i class="fas fa-info-circle"></i> Kamera hanya berfungsi di kamera belakang
                        </small> --}}

                    <div class="text-muted mt-1 mb-3">
                        <small>Scan QR code pada Hydrant untuk memulai pengecekan</small>
                    </div>

                    <!-- Manual Input -->
                    <div class="border-top pt-3">
                        <h6 class="text-center">Atau Input Manual</h6>
                        <form id="manual-form">
                            @csrf
                            <div class="mb-3">
                                <label for="qr_code" class="form-label">Nomor Hydrant</label>
                                <input type="text" class="form-control" id="qr_code" name="qr_code"
                                    placeholder="Masukkan nomor Hydrant" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search"></i> Cari Hydrant
                            </button>
                        </form>
                    </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-info-circle me-2"></i>
                    Informasi Pengecekan
                </div>
                <div class="card-body p-1 bg-light">
                    <div class="form-input">
                        <h6>Langkah-langkah:</h6>
                    <ol class="small">
                        <li>Klik "Mulai Scanner" untuk mengaktifkan kamera</li>
                        <li>Arahkan kamera ke QR code pada Hydrant</li>
                        <li>Scanner akan otomatis mendeteksi QR code</li>
                        <li>Atau input manual nomor Hydrant</li>
                        <li>Sistem akan validasi Hydrant dan akses Anda</li>
                        <li>Isi form pengecekan Hydrant</li>
                    </ol>

                    <h6 class="mt-3">Yang akan dicek:</h6>
                    <ul class="small">
                        <li>Pintu box mudah dibuka dan tertutup rapat</li>
                        <li>Coupling tidak longgar dan berkarat</li>
                        <li>Main valve berfungsi dengan baik</li>
                        <li>Selang tidak retak, lentur, dan tidak bocor</li>
                        <li>Nozzle bersih dan tidak tersumbat</li>
                        <li>Marking safety jelas dan lengkap</li>
                        <li>Panduan penggunaan tersedia</li>
                        <li>Catatan tambahan</li>
                    </ul>
                    </div>
                </div>
            </div>

            <!-- Result Display -->
            <div id="validation-result" class="mt-3" style="display: none;">
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
                    validateHydrant(qrCode);
                }
            });

            // QR Scanner functionality
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

                    // Coba constraint yang lebih fleksibel untuk laptop
                    const constraints = {
                        video: {
                            // Coba user dulu (kamera depan), lalu environment
                            facingMode: {
                                ideal: "environment"
                            }, // Untuk laptop
                            // Atau biarkan browser memilih otomatis
                            // facingMode: { ideal: ["user", "environment"] },

                            // Resolution lebih fleksibel
                            width: {
                                min: 320,
                                ideal: 640,
                                max: 1920
                            },
                            height: {
                                min: 240,
                                ideal: 480,
                                max: 1080
                            },

                            // Frame rate rendah untuk kompatibilitas
                            frameRate: {
                                ideal: 15,
                                max: 30
                            }
                        },
                        audio: false
                    };

                    // Timeout lebih lama untuk laptop
                    const timeoutPromise = new Promise((_, reject) => {
                        setTimeout(() => reject(new Error('Camera access timeout')), 15000);
                    });

                    const videoPromise = navigator.mediaDevices.getUserMedia(constraints);

                    videoStream = await Promise.race([videoPromise, timeoutPromise]);

                    const video = document.getElementById('preview');
                    video.srcObject = videoStream;
                    video.setAttribute("playsinline", true);

                    // Handle video play dengan error handling lebih baik
                    try {
                        await video.play();
                    } catch (playError) {
                        console.warn('Video play error, but continuing:', playError);
                    }

                    // Create canvas for QR detection
                    const canvas = document.createElement('canvas');
                    const canvasContext = canvas.getContext('2d');

                    isScanning = true;
                    $('#start-scanner').hide();
                    $('#stop-scanner').show();
                    $('#scanner-status').html(
                        '<div class="alert alert-success"><i class="fas fa-camera"></i> Scanner aktif - Arahkan ke QR Code</div>'
                        );

                    // Start scanning loop
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
                                    validateHydrant(code.data);
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

                    // Coba approach kedua dengan constraint lebih sederhana
                    if (error.name.includes('Timeout') || error.name === 'AbortError') {
                        await tryFallbackConstraints();
                    } else {
                        handleScannerError(error);
                    }
                }
            }

            // Fallback constraints untuk laptop
            async function tryFallbackConstraints() {
                try {
                    console.log('Mencoba constraint fallback...');

                    // Constraint sangat sederhana
                    const fallbackConstraints = {
                        video: {
                            // Tidak specify facingMode, biarkan browser pilih
                            width: {
                                min: 320,
                                max: 1280
                            },
                            height: {
                                min: 240,
                                max: 720
                            },
                            frameRate: {
                                min: 10,
                                max: 25
                            }
                        },
                        audio: false
                    };

                    const videoStream = await navigator.mediaDevices.getUserMedia(fallbackConstraints);

                    // Gunakan stream yang berhasil
                    const video = document.getElementById('preview');
                    video.srcObject = videoStream;

                    // Lanjutkan dengan scanning loop...
                    // (sama seperti di startJsQRScanner)

                } catch (fallbackError) {
                    console.error('Fallback juga gagal:', fallbackError);
                    handleScannerError(fallbackError);
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

                let errorMessage = 'Gagal mengakses kamera. ';
                let errorType = 'danger';

                if (error.name === 'NotAllowedError' || error.name === 'PermissionDeniedError') {
                    errorMessage = `
                    <h6><i class="fas fa-ban"></i> Izin Kamera Ditolak</h6>
                    <small>Silakan izinkan akses kamera di browser Anda:</small>
                    <ul class="small text-start mt-2">
                        <li>Klik ikon kamera/lock di address bar</li>
                        <li>Pilih "Allow" atau "Izinkan"</li>
                        <li>Refresh halaman dan coba lagi</li>
                    </ul>
                `;
                } else if (error.name === 'NotFoundError' || error.name === 'DevicesNotFoundError') {
                    errorMessage = `
                    <h6><i class="fas fa-camera-slash"></i> Kamera Tidak Ditemukan</h6>
                    <small>Perangkat Anda tidak memiliki kamera atau kamera tidak terdeteksi.</small>
                `;
                } else if (error.name === 'NotSupportedError') {
                    errorMessage = `
                    <h6><i class="fas fa-exclamation-triangle"></i> Browser Tidak Mendukung</h6>
                    <small>Browser Anda tidak mendukung fitur kamera. Gunakan Chrome, Firefox, atau Safari.</small>
                `;
                } else if (error.name === 'NotReadableError' || error.name === 'TrackStartError') {
                    errorMessage = `
                    <h6><i class="fas fa-times-circle"></i> Kamera Sedang Digunakan</h6>
                    <small>Kamera sedang digunakan oleh aplikasi lain. Tutup aplikasi lain yang menggunakan kamera.</small>
                `;
                } else if (error.message === 'Camera access timeout') {
                    errorMessage = `
                    <h6><i class="fas fa-clock"></i> Timeout Akses Kamera</h6>
                    <small>Kamera terlalu lama merespons. Coba refresh halaman atau gunakan browser lain.</small>
                `;
                } else {
                    errorMessage = `
                    <h6><i class="fas fa-exclamation-triangle"></i> Error: ${error.name}</h6>
                    <small>${error.message || 'Terjadi kesalahan tidak terduga'}</small>
                `;
                }

                $('#preview-container').html(`
                <div class="alert alert-${errorType}">
                    ${errorMessage}
                    <div class="mt-3">
                        <button onclick="location.reload()" class="btn btn-warning btn-sm me-2">
                            <i class="fas fa-redo"></i> Refresh Halaman
                        </button>
                        <button onclick="switchToManualInput()" class="btn btn-primary btn-sm">
                            <i class="fas fa-keyboard"></i> Input Manual
                        </button>
                    </div>
                </div>
            `);
            }

            function switchToManualInput() {
                $('#scanner-section').hide();
                $('#manual-input-section').show();
                $('#qr_code').focus();
            }

            function validateHydrant(numberHydrant) {
                // Show loading
                $('#validation-result').html(`
                <div class="alert alert-info">
                    <i class="fas fa-spinner fa-spin"></i> Memvalidasi Hydrant: <strong>${numberHydrant}</strong>
                </div>
            `).show();

                $.ajax({
                    url: "{{ route('hydrant-check.validate') }}",
                    method: 'POST',
                    data: {
                        qr_code: numberHydrant,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#validation-result').html(`
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i> Validasi Berhasil! Mengalihkan ke form pengecekan...
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
                        }
                    },
                    error: function(xhr) {
                        let message = 'Terjadi kesalahan saat validasi Hydrant';
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
                    }
                });
            }

            // Global function untuk restart scanner
            window.restartScanner = function() {
                $('#validation-result').hide();
                stopJsQRScanner();
                setTimeout(() => {
                    startJsQRScanner();
                }, 500);
            };

            window.switchToManualInput = switchToManualInput;

            // Cleanup ketika meninggalkan halaman
            $(window).on('beforeunload', function() {
                stopJsQRScanner();
            });

            // Auto start scanner ketika halaman dimuat
            setTimeout(() => {
                startJsQRScanner();
            }, 1000);
        });
    </script>
@endpush