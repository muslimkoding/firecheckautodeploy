<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan QR Code APAR</title>
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --light-bg: #f8f9fa;
        }

        body {
            background: #60AEBD;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-bottom: 80px; /* Space for footer */
        }

        .landing-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Header Styles */
        .app-header {
            text-align: center;
            padding: 2rem 1rem;
            color: white;
        }

        .app-logo {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            backdrop-filter: blur(10px);
        }

        .app-logo i {
            font-size: 2.5rem;
            color: white;
        }

        .app-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .app-subtitle {
            font-size: 1rem;
            opacity: 0.9;
            margin-bottom: 0;
        }

        /* Card Grid Styles */
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            padding: 0 1rem;
            margin-top: 2rem;
        }

        .menu-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            text-decoration: none;
            color: #333;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 200px;
        }

        .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
            text-decoration: none;
            color: #333;
        }

        .card-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            font-size: 1.8rem;
        }

        .card-icon.login {
            background: linear-gradient(135deg, var(--primary-color), #0a58ca);
        }

        .card-icon.scan {
            background: linear-gradient(135deg, var(--success-color), #157347);
        }

        .card-icon.history {
            background: linear-gradient(135deg, var(--warning-color), #ffcd39);
        }

        .card-icon.report {
            background: linear-gradient(135deg, var(--danger-color), #b02a37);
        }

        .card-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .card-description {
            font-size: 0.9rem;
            color: #666;
            line-height: 1.4;
        }

        /* Android-like Bottom Navigation */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            padding: 0.5rem 1rem;
            display: flex;
            justify-content: space-around;
            align-items: center;
            box-shadow: 0 -2px 20px rgba(0, 0, 0, 0.1);
        }

        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0.5rem;
            text-decoration: none;
            color: #666;
            transition: all 0.3s ease;
            border-radius: 12px;
            min-width: 60px;
        }

        .nav-item.active {
            color: var(--primary-color);
        }

        .nav-item:hover {
            color: var(--primary-color);
            background: rgba(13, 110, 253, 0.1);
        }

        .nav-icon {
            font-size: 1.4rem;
            margin-bottom: 0.25rem;
        }

        .nav-label {
            font-size: 0.7rem;
            font-weight: 500;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .app-title {
                font-size: 1.8rem;
            }
            
            .menu-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .menu-card {
                padding: 1.5rem;
                min-height: 180px;
            }
            
            .card-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .app-header {
                padding: 1.5rem 1rem;
            }
            
            .app-title {
                font-size: 1.5rem;
            }
            
            .bottom-nav {
                padding: 0.5rem;
            }
            
            .nav-item {
                min-width: 50px;
                padding: 0.4rem;
            }
            
            .nav-icon {
                font-size: 1.2rem;
            }
            
            .nav-label {
                font-size: 0.65rem;
            }
        }

        /* Additional Features */
        .feature-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--danger-color);
            color: white;
            border-radius: 10px;
            padding: 4px 8px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .card-wrapper {
            position: relative;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            padding: 0 1rem;
            margin-top: 2rem;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            padding: 1rem;
            text-align: center;
            backdrop-filter: blur(10px);
            border: 1px solid #dadada;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .stat-number {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.8rem;
            color: #666;
        }


        body {
            /* background: linear-gradient(135deg, #7cea66 0%, #0fadcd 100%); */
            /* background: rgb(232, 232, 232); */
            background: #EBF7F9;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        /* .scan-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        } */
        #preview {
            width: 100%;
            border-radius: 10px;
        }
        .manual-input {
            display: none;
        }
        .form-input {
            border: 1px solid rgba(0, 0, 0, 0.1); /* garis luar halus */
            border-radius: 10px; /* sudut melengkung */
            padding: 1rem; /* ruang dalam agar lega */
            background-color: #fff; /* pastikan kontras kalau background luar abu-abu */
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.03); /* sentuhan shadow lembut opsional */
        }
        .card {
            border-radius: 0.8rem !important;
            overflow: hidden;
            border-color: #f8f9fa;

        }

        /* pastikan tidak ada border bawah yang merusak lengkungan */
        .card .card-header {
            border-bottom: none;
            background-color: #f8f9fa;
        }
    </style>
</head>

<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card border rounded-4">
                <div class="card-header">
                    <div class="">Scan APAR</div>
                </div>
                <div class="card-body p-1 bg-light">
                    <div class="form-input">
                        <div class="scan-container p-4">

                            <div class="text-center mb-4">
                                <h2 class="fw-bold text-primary">SCAN QR CODE APAR</h2>
                                <p class="text-muted">Arahkan kamera ke QR code APAR</p>
                            </div>
            
                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif
            
                            <form action="{{ route('public.apar.scan.submit') }}" method="POST" id="scanForm">
                                @csrf
            
                                <!-- Camera Preview -->
                                <div id="cameraBox">
                                    <video id="preview" playsinline></video>
                                    <canvas id="canvas" style="display:none;"></canvas>
                                </div>
            
                                <!-- Manual Input -->
                                <div class="manual-input mt-3" id="manualInput">
                                    <label>Kode APAR</label>
                                    <input type="text" name="qr_code" id="qr_code" class="form-control form-control-lg">
                                    <button class="btn btn-primary w-100 mt-3">Cek Riwayat</button>
                                </div>
                            </form>
            
                            <button class="btn btn-light border w-100 mt-3" id="toggleInput">Input Manual</button>
            
                        </div>
                    </div>
                </div>
            </div>

            

        </div>
    </div>
</div>

    <!-- Android-like Bottom Navigation -->
    <nav class="bottom-nav">
        <a href="{{ url('/') }}" class="nav-item">
            <i class="fas fa-home nav-icon"></i>
            <span class="nav-label">Home</span>
        </a>
        <a href="{{ route('public.apar.scan') }}" class="nav-item active">
            <i class="fas fa-qrcode nav-icon"></i>
            <span class="nav-label">Apar</span>
        </a>
        <a href="{{ route('public.apar.latest') }}" class="nav-item">
            <i class="fas fa-history nav-icon"></i>
            <span class="nav-label">Riwayat</span>
        </a>
        <a href="{{ route('public.hydrant.scan') }}" class="nav-item">
            <i class="fas fa-qrcode nav-icon"></i>
            <span class="nav-label">Hydrant</span>
        </a>
        <a href="{{ route('public.apar.contacts') }}" class="nav-item">
            <i class="fa-solid fa-phone nav-icon"></i>
            <span class="nav-label">Emergency</span>
        </a>
    </nav>

<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
 <!-- Font Awesome for Icons -->
 <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

 <script>
     // Simple active state management for bottom nav
     document.addEventListener('DOMContentLoaded', function() {
         const navItems = document.querySelectorAll('.nav-item');
         
         navItems.forEach(item => {
             item.addEventListener('click', function() {
                 navItems.forEach(nav => nav.classList.remove('active'));
                 this.classList.add('active');
             });
         });

         // Add subtle animation to cards on load
         const cards = document.querySelectorAll('.menu-card');
         cards.forEach((card, index) => {
             card.style.opacity = '0';
             card.style.transform = 'translateY(20px)';
             
             setTimeout(() => {
                 card.style.transition = 'all 0.5s ease';
                 card.style.opacity = '1';
                 card.style.transform = 'translateY(0)';
             }, index * 100);
         });
     });
 </script>

<script>
    let video = document.getElementById('preview');
    let canvas = document.getElementById('canvas');
    let ctx = canvas.getContext('2d');
    let scanning = false;

    // Start camera
    navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } })
        .then(function(stream) {
            video.srcObject = stream;
            video.setAttribute("playsinline", true);
            video.play();
            scanning = true;
            scanQR();
        })
        .catch(() => {
            alert("Kamera tidak bisa dibuka. Gunakan input manual.");
        });

    function scanQR() {
        if (!scanning) return;

        if (video.readyState === video.HAVE_ENOUGH_DATA) {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

            let imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            let qr = jsQR(imageData.data, imageData.width, imageData.height);

            if (qr) {
                scanning = false;

                // Masukkan hasil scan
                document.getElementById('qr_code').value = qr.data;

                // Submit otomatis
                document.getElementById('scanForm').submit();
            }
        }

        requestAnimationFrame(scanQR);
    }

    // Toggle input manual
    document.getElementById('toggleInput').addEventListener('click', function() {
        let manual = document.getElementById('manualInput');
        let cameraBox = document.getElementById('cameraBox');

        if (manual.style.display === "none" || manual.style.display === "") {
            manual.style.display = "block";
            cameraBox.style.display = "none";
            this.textContent = "Scan QR";
        } else {
            manual.style.display = "none";
            cameraBox.style.display = "block";
            this.textContent = "Input Manual";
        }
    });
</script>

</body>
</html>


{{-- <!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan QR Code APAR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .scan-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .qr-scanner {
            border: 3px dashed #007bff;
            border-radius: 10px;
            min-height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        .qr-scanner:hover {
            border-color: #0056b3;
            background-color: #f8f9fa;
        }
        .manual-input {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="scan-container p-4">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold text-primary">SCAN QR CODE APAR</h2>
                        <p class="text-muted">Scan QR code pada APAR untuk melihat riwayat pengecekan</p>
                    </div>

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('public.apar.scan.submit') }}" method="POST" id="scanForm">
                        @csrf
                        
                        <!-- QR Scanner Area -->
                        <div class="qr-scanner mb-3" id="qrScanner">
                            <div class="text-center">
                                <i class="fas fa-camera fa-3x text-primary mb-3"></i>
                                <p class="mb-0">Klik untuk scan QR Code</p>
                                <small class="text-muted">atau input manual kode APAR</small>
                            </div>
                        </div>

                        <!-- Manual Input -->
                        <div class="manual-input" id="manualInput">
                            <div class="mb-3">
                                <label for="qr_code" class="form-label">Kode APAR</label>
                                <input type="text" class="form-control form-control-lg" 
                                       id="qr_code" name="qr_code" 
                                       placeholder="Masukkan kode APAR atau scan QR" required>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-search me-2"></i>Cek Riwayat
                                </button>
                                <button type="button" class="btn btn-outline-secondary" id="btnBackToScan">
                                    <i class="fas fa-camera me-2"></i>Kembali ke Scan
                                </button>
                            </div>
                        </div>

                        <!-- Hidden file input for QR scan -->
                        <input type="file" id="qrFile" accept="image/*" capture="environment" style="display: none;">
                    </form>

                    <div class="text-center mt-3">
                        <button type="button" class="btn btn-link" id="toggleInput">
                            Input Kode Manual
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js"></script>
    <!-- Include QR Scanner Library -->
    <script src="https://unpkg.com/html5-qrcode/minified/html5-qrcode.min.js"></script>
    
    <script>
        // Toggle between QR scan and manual input
        document.getElementById('toggleInput').addEventListener('click', function() {
            const qrScanner = document.getElementById('qrScanner');
            const manualInput = document.getElementById('manualInput');
            const toggleBtn = document.getElementById('toggleInput');

            if (manualInput.style.display === 'none' || !manualInput.style.display) {
                qrScanner.style.display = 'none';
                manualInput.style.display = 'block';
                toggleBtn.textContent = 'Scan QR Code';
            } else {
                qrScanner.style.display = 'flex';
                manualInput.style.display = 'none';
                toggleBtn.textContent = 'Input Kode Manual';
            }
        });

        // Back to scan button
        document.getElementById('btnBackToScan').addEventListener('click', function() {
            document.getElementById('qrScanner').style.display = 'flex';
            document.getElementById('manualInput').style.display = 'none';
            document.getElementById('toggleInput').textContent = 'Input Kode Manual';
        });

        // QR Code Scanner
        const qrScanner = new Html5Qrcode("qrScanner");
        
        document.getElementById('qrScanner').addEventListener('click', function() {
            // Start QR scanning
            qrScanner.start(
                { facingMode: "environment" },
                {
                    fps: 10,
                    qrbox: { width: 250, height: 250 }
                },
                (decodedText) => {
                    // QR code scanned successfully
                    document.getElementById('qr_code').value = decodedText;
                    document.getElementById('scanForm').submit();
                },
                (errorMessage) => {
                    // QR scanning failed, ignore errors
                }
            ).catch(err => {
                console.error("QR Scanner error:", err);
                // Fallback to manual input if scanner fails
                document.getElementById('qrScanner').style.display = 'none';
                document.getElementById('manualInput').style.display = 'block';
                document.getElementById('toggleInput').textContent = 'Scan QR Code';
            });
        });
    </script>
</body>
</html> --}}