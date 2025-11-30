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
                <div class="card-body text-center p-1 bg-light">
                    <div class="form-input">
                        <!-- QR Code Scanner Area -->
                    <div id="qr-scanner" class="mb-3">
                        <video id="preview" style="width: 100%; height: 300px; border: 2px dashed #dee2e6; border-radius: 8px; background: #f8f9fa;"></video>
                        <div class="scanner-controls mt-2">
                            <button id="start-scanner" class="btn btn-success btn-sm">
                                <i class="fas fa-camera"></i> Mulai Scanner
                            </button>
                            <button id="stop-scanner" class="btn btn-danger btn-sm" style="display: none;">
                                <i class="fas fa-stop"></i> Stop Scanner
                            </button>
                            <button id="switch-camera" class="btn btn-info btn-sm" style="display: none;">
                                <i class="fas fa-sync-alt"></i> Ganti Kamera
                            </button>
                        </div>
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
                        <li>Arahkan kamera ke QR code pada APAR</li>
                        <li>Scanner akan otomatis mendeteksi QR code</li>
                        <li>Atau input manual nomor APAR</li>
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

{{-- @push('scripts')
    <!-- Instascan Library -->
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>

    <script>
        $(document).ready(function() {
            let scanner = null;
            let cameras = [];
            let currentCameraIndex = 0;
            let isScanning = false;

            // Initialize dengan placeholder
            showScannerPlaceholder();

            // Manual form submission
            $('#manual-form').on('submit', function(e) {
                e.preventDefault();
                const qrCode = $('#qr_code').val().trim();
                if (qrCode) {
                    validateApar(qrCode);
                }
            });

            // QR Scanner functionality
            $('#start-scanner').on('click', function() {
                startScanner();
            });

            $('#stop-scanner').on('click', function() {
                stopScanner();
            });

            $('#switch-camera').on('click', function() {
                switchCamera();
            });

            function showScannerPlaceholder() {
                $('#preview').html(`
                    <div class="scanner-placeholder">
                        <i class="fas fa-camera"></i>
                        <small>Klik "Mulai Scanner" untuk mengaktifkan kamera</small>
                    </div>
                `);
            }

            function showScannerLoading() {
                $('#preview').html(`
                    <div class="scanner-loading">
                        <div class="spinner-border text-primary" role="status"></div>
                        <small>Menyiapkan kamera...</small>
                    </div>
                `);
            }

            async function startScanner() {
                if (isScanning) return;

                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                    alert("Browser Anda tidak mendukung kamera.");
                    return;
                }

                try {
                    showScannerLoading();

                    // Dapatkan daftar kamera yang tersedia
                    cameras = await Instascan.Camera.getCameras();
                    
                    if (cameras.length === 0) {
                        throw new Error('Tidak ada kamera yang ditemukan pada perangkat ini.');
                    }

                    // Pilih kamera (utamakan back camera)
                    const backCamera = cameras.find(camera => 
                        camera.name.toLowerCase().includes('back') || 
                        camera.name.toLowerCase().includes('rear')
                    );
                    
                    currentCameraIndex = backCamera ? cameras.indexOf(backCamera) : 0;

                    // Buat instance scanner
                    scanner = new Instascan.Scanner({
                        video: document.getElementById('preview'),
                        mirror: false,
                        backgroundScan: true,
                        scanPeriod: 5,
                        refractoryPeriod: 5000
                    });

                    // Handle ketika QR code terdeteksi
                    scanner.addListener('scan', function(content) {
                        console.log('QR Code berhasil di-scan:', content);
                        stopScanner();
                        validateApar(content);
                    });

                    // Mulai scanning dengan kamera yang dipilih
                    await scanner.start(cameras[currentCameraIndex]);

                    // Update UI state
                    isScanning = true;
                    $('#start-scanner').hide();
                    $('#stop-scanner').show();
                    
                    // Tampilkan tombol ganti kamera jika ada lebih dari 1 kamera
                    if (cameras.length > 1) {
                        $('#switch-camera').show();
                    }

                    console.log('Scanner berhasil dimulai dengan kamera:', cameras[currentCameraIndex].name);

                } catch (error) {
                    console.error("Gagal memulai scanner:", error);
                    handleScannerError(error);
                }
            }

            function stopScanner() {
                if (!isScanning || !scanner) return;

                try {
                    scanner.stop();
                    cleanupScanner();
                    console.log("Scanner berhasil dihentikan");
                } catch (error) {
                    console.error("Error menghentikan scanner:", error);
                    cleanupScanner();
                }
            }

            function cleanupScanner() {
                isScanning = false;
                scanner = null;
                
                // Reset UI
                $('#start-scanner').show();
                $('#stop-scanner').hide();
                $('#switch-camera').hide();
                
                // Tampilkan placeholder
                showScannerPlaceholder();
            }

            async function switchCamera() {
                if (!isScanning || cameras.length <= 1) return;

                try {
                    // Hentikan scanner sementara
                    await scanner.stop();
                    
                    // Pilih kamera berikutnya
                    currentCameraIndex = (currentCameraIndex + 1) % cameras.length;
                    
                    // Mulai ulang dengan kamera baru
                    await scanner.start(cameras[currentCameraIndex]);
                    
                    console.log('Berhasil ganti kamera ke:', cameras[currentCameraIndex].name);
                    
                } catch (error) {
                    console.error('Gagal mengganti kamera:', error);
                    // Coba mulai ulang dengan kamera sebelumnya
                    try {
                        await scanner.start(cameras[currentCameraIndex]);
                    } catch (e) {
                        handleScannerError(e);
                    }
                }
            }

            function handleScannerError(error) {
                let errorMessage = 'Gagal mengakses kamera. ';
                
                if (error.name === 'NotAllowedError' || error.message.includes('Permission')) {
                    errorMessage = 'Izin kamera ditolak. Silakan izinkan akses kamera di browser Anda.';
                } else if (error.name === 'NotFoundError' || error.message.includes('No cameras')) {
                    errorMessage = 'Tidak ada kamera yang ditemukan pada perangkat ini.';
                } else if (error.name === 'NotSupportedError') {
                    errorMessage = 'Browser tidak mendukung fitur kamera.';
                } else if (error.name === 'NotReadableError') {
                    errorMessage = 'Kamera sedang digunakan oleh aplikasi lain.';
                } else {
                    errorMessage = 'Error: ' + (error.message || error);
                }

                $('#preview').html(`
                    <div class="alert alert-danger text-center m-2">
                        <i class="fas fa-exclamation-triangle"></i><br>
                        <small>${errorMessage}</small>
                    </div>
                `);
                
                cleanupScanner();
            }

            function validateApar(numberApar) {
                // Show loading
                $('#validation-result').html(`
                    <div class="alert alert-info">
                        <i class="fas fa-spinner fa-spin"></i> Memvalidasi APAR: <strong>${numberApar}</strong>
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
                    }
                });
            }

            // Global function untuk restart scanner
            window.restartScanner = function() {
                $('#validation-result').hide();
                startScanner();
            };

            // Cleanup ketika meninggalkan halaman
            $(window).on('beforeunload', function() {
                if (isScanning && scanner) {
                    stopScanner();
                }
            });
        });
    </script>
@endpush --}}
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
                     // Access camera
                     videoStream = await navigator.mediaDevices.getUserMedia({
                         video: {
                             facingMode: "environment",
                             width: { ideal: 1280 },
                             height: { ideal: 720 }
                         }
                     });
 
                     const video = document.getElementById('preview');
                     video.srcObject = videoStream;
                     video.setAttribute("playsinline", true); // Required for iOS
                     await video.play();
 
                     // Create canvas for QR detection
                     const canvas = document.createElement('canvas');
                     const canvasContext = canvas.getContext('2d');
                     
                     isScanning = true;
                     $('#start-scanner').hide();
                     $('#stop-scanner').show();
 
                     // Start scanning loop
                     function scanFrame() {
                         if (!isScanning) return;
 
                         if (video.readyState === video.HAVE_ENOUGH_DATA) {
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
                     videoStream.getTracks().forEach(track => track.stop());
                     videoStream = null;
                 }
                 
                 $('#start-scanner').show();
                 $('#stop-scanner').hide();
                 
                 const video = document.getElementById('preview');
                 video.srcObject = null;
             }
 
             function handleScannerError(error) {
                 let errorMessage = 'Gagal mengakses kamera. ';
                 
                 if (error.name === 'NotAllowedError') {
                     errorMessage = 'Izin kamera ditolak. Silakan izinkan akses kamera di browser Anda.';
                 } else if (error.name === 'NotFoundError') {
                     errorMessage = 'Tidak ada kamera yang ditemukan pada perangkat ini.';
                 } else if (error.name === 'NotSupportedError') {
                     errorMessage = 'Browser tidak mendukung fitur kamera.';
                 } else if (error.name === 'NotReadableError') {
                     errorMessage = 'Kamera sedang digunakan oleh aplikasi lain.';
                 } else {
                     errorMessage = 'Error: ' + error.message;
                 }
 
                 $('#preview').parent().html(`
                     <div class="alert alert-danger text-center m-2">
                         <i class="fas fa-exclamation-triangle"></i><br>
                         <small>${errorMessage}</small>
                     </div>
                 `);
             }
 

            function validateApar(numberApar) {
                // Show loading
                $('#validation-result').html(`
                    <div class="alert alert-info">
                        <i class="fas fa-spinner fa-spin"></i> Memvalidasi APAR: <strong>${numberApar}</strong>
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
                    }
                });
            }

            // Global function untuk restart scanner
            window.restartScanner = function() {
                $('#validation-result').hide();
                startScanner();
            };

            // Cleanup ketika meninggalkan halaman
            $(window).on('beforeunload', function() {
                if (isScanning && scanner) {
                    stopScanner();
                }
            });
        });
    </script>
@endpush