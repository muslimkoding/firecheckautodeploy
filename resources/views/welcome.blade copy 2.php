

<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FireCheck - Sistem Pengecekan APAR & Hydrant</title>
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
    </style>
</head>

<body>
    <div class="landing-container">
        <!-- Header Section -->
        <header class="app-header">
            <div class="app-logo">
                <i class="fas fa-fire-extinguisher fs-1"></i>
            </div>
            <h1 class="app-title">FireCheck</h1>
            <p class="app-subtitle">Sistem Monitoring APAR & Hydrant</p>
        </header>

        <!-- Quick Stats (Optional) -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-number">150+</div>
                <div class="stat-label">APAR Terdaftar</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">89%</div>
                <div class="stat-label">Dalam Kondisi Baik</div>
            </div>
            <div class="stat-card">
                <div class="stat-number">24/7</div>
                <div class="stat-label">Monitoring</div>
            </div>
        </div>

        <!-- Main Menu Grid -->
        <div class="menu-grid mb-4">
            <div class="card-wrapper">
                <a href="{{ route('login') }}" class="menu-card">
                    <div class="card-icon login">
                        <i class="text-white fas fa-sign-in-alt"></i>
                    </div>
                    <h3 class="card-title">Login Dashboard</h3>
                    <p class="card-description">Akses panel admin untuk mengelola data pengecekan dan laporan</p>
                </a>
            </div>

            <div class="card-wrapper">
                <a href="{{ route('public.apar.scan') }}" class="menu-card">
                    <div class="card-icon scan">
                        <i class="text-white fas fa-qrcode"></i>
                    </div>
                    <h3 class="card-title">Scan Cek Riwayat</h3>
                    <p class="card-description">Scan QR code untuk melihat riwayat pengecekan APAR & Hydrant</p>
                </a>
            </div>

            <div class="card-wrapper">
                <a href="#" class="menu-card">
                    <div class="card-icon history">
                        <i class="text-white fas fa-history"></i>
                    </div>
                    <h3 class="card-title">Riwayat Lengkap</h3>
                    <p class="card-description">Lihat semua data historis pengecekan dan maintenance</p>
                </a>
            </div>

            <div class="card-wrapper">
                <a href="#" class="menu-card">
                    <div class="card-icon report">
                        <i class="text-white fas fa-chart-bar"></i>
                    </div>
                    <h3 class="card-title">Laporan & Statistik</h3>
                    <p class="card-description">Analisis data dan generate laporan periodik</p>
                    <span class="feature-badge">SOON</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Android-like Bottom Navigation -->
    <nav class="bottom-nav">
        <a href="#" class="nav-item active">
            <i class="fas fa-home nav-icon"></i>
            <span class="nav-label">Home</span>
        </a>
        <a href="{{ route('public.apar.scan') }}" class="nav-item">
            <i class="fas fa-qrcode nav-icon"></i>
            <span class="nav-label">Scan</span>
        </a>
        <a href="{{ route('login') }}" class="nav-item">
            <i class="fas fa-tachometer-alt nav-icon"></i>
            <span class="nav-label">Dashboard</span>
        </a>
        <a href="{{ route('public.apar.latest') }}" class="nav-item">
            <i class="fas fa-history nav-icon"></i>
            <span class="nav-label">Riwayat</span>
        </a>
        <a href="{{ route('public.apar.contacts') }}" class="nav-item">
            <i class="fa-solid fa-phone nav-icon"></i>
            <span class="nav-label">Emergency</span>
        </a>
    </nav>

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
</body>

</html> 
