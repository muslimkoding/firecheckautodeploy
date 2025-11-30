<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nomor Darurat Kebakaran</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --info-color: #0dcaf0;
            --light-bg: #f8f9fa;
        }
        
        body {
            background: #EBF7F9;
            background-image: url("{{ asset('assets/images/supgraf-injourney-13-min.png') }}");
            background-position: bottom right;
            background-repeat: no-repeat;
            background-size: 600px auto;
            background-attachment: fixed;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding-bottom: 80px;
        }

        .header-section {
            background: rgb(232, 232, 232);
            border-radius: 20px;
            margin: 14px;
        }

        .contact-card {
            border-radius: 15px;
            margin-bottom: 1rem;
            border: 1px solid #e4e4e4;
            background: white;
            transition: all 0.3s ease;
        }

        /* .contact-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        } */

        .contact-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: white;
        }

        .emergency-badge {
            font-size: 0.7rem;
            padding: 0.3rem 0.6rem;
            border-radius: 10px;
        }

        .quick-call-btn {
            border-radius: 25px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        /* .quick-call-btn:hover {
            transform: scale(1.05);
        } */

        .section-title {
            border-left: 4px solid;
            padding-left: 1rem;
            margin: 2rem 0 1rem 0;
            font-weight: 700;
        }

        .section-emergency {
            border-left-color: var(--danger-color);
            color: var(--danger-color);
        }

        .section-airport {
            border-left-color: var(--info-color);
            color: var(--info-color);
        }

        .section-personnel {
            border-left-color: var(--success-color);
            color: var(--success-color);
        }

        .section-facility {
            border-left-color: var(--warning-color);
            color: var(--warning-color);
        }

        /* Bottom Navigation */
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
            z-index: 1000;
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

        /* Quick Action Buttons */
        .quick-actions {
            position: sticky;
            top: 10px;
            z-index: 100;
            background: white;
            border-radius: 15px;
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        @media (max-width: 768px) {
            .container {
                padding: 0 10px;
            }
            
            .contact-card {
                margin-bottom: 0.75rem;
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="container mt-3">
        <div class="card contact-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <img src="{{ asset('assets/images/logo-injourney-airports.png') }}" class="img-fluid"
                        style="width: 8rem;" alt="">
                    <img src="{{ asset('assets/images/logo-arff-min.png') }}" class="img-fluid" style="width: 2.5rem;"
                        alt="">
                    <img src="{{ asset('assets/images/(UPG)-Sultan-Hasanuddin.png') }}" class="img-fluid"
                        style="width: 8rem;" alt="">
                </div>
            </div>
        </div>

        <!-- Page Title -->
        <div class="card contact-card mt-3">
            <div class="card-body text-center">
                <h4 class="fw-bold mb-1 text-danger">
                    <i class="fas fa-phone-alt me-2"></i>NOMOR DARURAT KEBAKARAN
                </h4>
                <p class="text-muted mb-0">Daftar kontak penting untuk situasi darurat</p>
                <small class="text-muted">
                    Total: {{ count($emergencyContacts) }} nomor | Update: {{ now()->format('d-m-Y') }}
                </small>
            </div>
        </div>

        <!-- Quick Emergency Actions -->
        <div class="quick-actions">
            <div class="row g-2">
                <div class="col-6">
                    <button class="btn btn-danger w-100 quick-call-btn" onclick="callNumber('113')">
                        <i class="fas fa-fire me-2"></i>Pemadam<br>
                        <small>113</small>
                    </button>
                </div>
                <div class="col-6">
                    <button class="btn btn-success w-100 quick-call-btn" onclick="callNumber('118')">
                        <i class="fas fa-ambulance me-2"></i>Ambulans<br>
                        <small>118</small>
                    </button>
                </div>
                <div class="col-6">
                    <button class="btn btn-primary w-100 quick-call-btn" onclick="callNumber('110')">
                        <i class="fas fa-shield-alt me-2"></i>Polisi<br>
                        <small>110</small>
                    </button>
                </div>
                <div class="col-6">
                    <button class="btn btn-warning w-100 quick-call-btn" onclick="callNumber('115')">
                        <i class="fas fa-helicopter me-2"></i>Basarnas<br>
                        <small>115</small>
                    </button>
                </div>
            </div>
        </div>

        <!-- Emergency Contacts by Category -->
        @foreach($groupedContacts as $type => $group)
            @if(count($group['contacts']) > 0)
                <div class="section-title section-{{ $type }}">
                    <h5 class="mb-2">
                        <i class="fas 
                            @if($type == 'emergency') fa-exclamation-triangle
                            @elseif($type == 'airport') fa-plane
                            @elseif($type == 'personnel') fa-users
                            @elseif($type == 'facility') fa-cogs
                            @endif me-2">
                        </i>
                        {{ $group['title'] }}
                    </h5>
                    <small class="text-muted">{{ count($group['contacts']) }} kontak</small>
                </div>

                @foreach($group['contacts'] as $contact)
                    <div class="card contact-card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <div class="contact-icon bg-{{ $contact['color'] }}">
                                        <i class="fas {{ $contact['icon'] }}"></i>
                                    </div>
                                </div>
                                <div class="col">
                                    <h6 class="mb-1 fw-bold">{{ $contact['name'] }}</h6>
                                    <p class="mb-1 text-muted small">{{ $contact['description'] }}</p>
                                    <div class="d-flex align-items-center">
                                        <span class="badge emergency-badge bg-{{ $contact['color'] }} me-2">
                                            <i class="fas fa-phone me-1"></i>{{ $contact['number'] }}
                                        </span>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <button class="btn btn-outline-{{ $contact['color'] }} btn-sm quick-call-btn" 
                                            onclick="callNumber('{{ $contact['number'] }}')">
                                        <i class="fas fa-phone me-1"></i>Call
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        @endforeach

        <!-- Emergency Instructions -->
        <div class="card contact-card mt-4">
            <div class="card-header bg-warning text-dark">
                <h6 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>Instruksi Darurat
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="fas fa-fire text-danger me-2"></i>Saat Terjadi Kebakaran:</h6>
                        <ul class="small">
                            <li>Jangan panik, tetap tenang</li>
                            <li>Hubungi pemadam kebakaran segera</li>
                            <li>Gunakan APAR terdekat jika memungkinkan</li>
                            <li>Evakuasi area dengan tertib</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fas fa-phone text-primary me-2"></i>Saat Menelepon Darurat:</h6>
                        <ul class="small">
                            <li>Sebutkan lokasi dengan jelas</li>
                            <li>Jelaskan jenis keadaan darurat</li>
                            <li>Berikan informasi korban (jika ada)</li>
                            <li>Tunggu instruksi lebih lanjut</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Last Updated -->
        <div class="text-center mt-4 mb-5">
            <small class="text-muted">
                <i class="fas fa-sync-alt me-1"></i>
                Data diperbarui: {{ now()->format('d-m-Y H:i') }}
            </small>
        </div>
    </div>

    <!-- Android-like Bottom Navigation -->
    <nav class="bottom-nav">
        <a href="{{ url('/') }}" class="nav-item">
            <i class="fas fa-home nav-icon"></i>
            <span class="nav-label">Home</span>
        </a>
        <a href="{{ route('public.apar.scan') }}" class="nav-item">
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
        <a href="{{ route('public.apar.contacts') }}" class="nav-item active">
            <i class="fas fa-phone nav-icon"></i>
            <span class="nav-label">Emergency</span>
        </a>
    </nav>

    <script>
        // Function to handle phone calls
        function callNumber(phoneNumber) {
            // Remove any non-numeric characters except +
            const cleanNumber = phoneNumber.replace(/[^\d+]/g, '');
            
            // Confirm before calling
            if (confirm(`Apakah Anda yakin ingin menelepon:\n${phoneNumber}?`)) {
                // For web - open tel link
                window.open(`tel:${cleanNumber}`, '_self');
                
                // For mobile apps, this will trigger the phone dialer
            }
        }

        // Add click animation to contact cards
        document.addEventListener('DOMContentLoaded', function() {
            const contactCards = document.querySelectorAll('.contact-card');
            contactCards.forEach(card => {
                card.addEventListener('click', function() {
                    this.style.transform = 'scale(0.98)';
                    setTimeout(() => {
                        this.style.transform = '';
                    }, 150);
                });
            });

            // Make entire card clickable for phone numbers
            const callButtons = document.querySelectorAll('.quick-call-btn');
            callButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.stopPropagation(); // Prevent event bubbling
                });
            });
        });

        // Quick search functionality
        function searchContacts() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toLowerCase();
            const cards = document.querySelectorAll('.contact-card');
            
            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                if (text.includes(filter)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>