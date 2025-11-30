<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
<title>Dashboard - @yield('title')</title>

<!-- Open Graph Meta Tags -->
<meta property="og:title" content="FireCheck UPG - Sistem Monitoring APAR & Hydrant">
<meta property="og:description" content="Sistem monitoring dan pengecekan APAR & Hydrant Bandara Sultan Hasanuddin">
<meta property="og:image" content="{{ asset('assets/images/android-chrome-192x192.png') }}">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:type" content="website">
<meta property="og:site_name" content="FireCheck UPG">

<!-- Twitter Card Meta Tags -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="FireCheck UPG - Sistem Monitoring APAR & Hydrant">
<meta name="twitter:description" content="Sistem monitoring dan pengecekan APAR & Hydrant Bandara Sultan Hasanuddin">
<meta name="twitter:image" content="{{ asset('assets/images/android-chrome-192x192.png') }}">
<meta name="twitter:site" content="@firecheckupg">

<!-- Additional Meta Tags -->
<meta name="description" content="Sistem monitoring dan pengecekan APAR & Hydrant Bandara Sultan Hasanuddin">
<meta name="keywords" content="firecheck, apar, hydrant, safety, bandara, upg, sultan hasanuddin">
<meta name="author" content="FireCheck UPG">

<!-- Favicon -->
<link rel="icon" type="image/x-icon" href="{{ asset('assets/images/favicon.ico') }}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/apple-touch-icon.png') }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/favicon-32x32.png') }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/favicon-16x16.png') }}">
<link rel="manifest" href="{{ asset('assets/images/site.webmanifest') }}">

@vite(['resources/sass/app.scss', 'resources/css/styles.css', 'resources/js/app.js'])
<script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
@stack('styles')
