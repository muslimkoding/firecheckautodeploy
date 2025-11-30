<html lang="en" data-kit-theme="default">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Naskah Dinas | Login</title>
    <meta name="csrf-token" content="xrfw3Wb6qZaBVPVKQUpK9SC2msPSGcKu0YopUCEC">
    <link rel="icon" type="image/png"
        href="https://nadia.injourneyairports.id/assets/components/kit/core/img/favicon.ico">

    @vite(['resources/sass/app.scss', 'resources/css/styles.css', 'resources/js/app.js'])


    <style>
        .form-container {
            height: calc(100dvh - 28px - 34px);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .slider-login {
            pointer-events: none;
            width: 100%;
            height: 100dvh;
            position: absolute;
            left: 0;
            top: 0;
        }


        .slider-login .owl-item {
            height: 100dvh;
            /* width: 100vw; */
            object-fit: cover;
            object-position: center;
            overflow: hidden;
        }

        .bg-slider {
            width: 100vw !important;
            height: 100vh;
            object-fit: cover;
            object-position: center center;
        }

        .owl-theme.owl-background .item {
            height: 100vh;
            width: 100vw;
        }

        /* .owl-carousel .owl-item img {
                                                                                                                                                                                                width: unset !important;
                                                                                                                                                                                            } */
    </style>
    <link rel="stylesheet" type="text/css"
        href="https://nadia.injourneyairports.id/assets/components/kit/vendors/style.css">
    <link rel="stylesheet" type="text/css"
        href="https://nadia.injourneyairports.id/assets/components/kit/core/style.css">
    <link rel="stylesheet" type="text/css"
        href="https://nadia.injourneyairports.id/assets/components/cleanui/styles/style.css">



    <script src="https://nadia.injourneyairports.id/assets/vendors/jquery/jquery-3.7.1.min.js"></script>







<body class="cui__layout--cardsShadow">
    <div class="initial__loading" style="display: none;"></div>
    <div class="cui__layout cui__layout--hasSider">
        <div class="cui__layout">
            <div class="cui__layout__content">

                <div class="cui__utils__content">
                    <div class="position-relative">

                        <div class="position-relative form-container" style="z-index: 2;">
                            <div class="container">
                                <div class="row justify-content-end">
                                    <div class="col-12 col-lg-4">
                                        <div class="text-center my-5">
                                            <div class="row g-3">
                                                <div class="col-6 align-content-center">
                                                    {{-- <img src="{{ asset('assets/images/bg-general-injourney.jpg') }}"
                                                        alt="Naskah Dinas" class="w-100"> --}}
                                                </div>
                                                <div class="col-6">
                                                    <img src="{{ asset('assets/images/logo-injourney-airports.png') }}"
                                                        alt="Logo" class="w-100">
                                                </div>
                                            </div>
                                            <h4 style="color: #6B7A99">
                                                Log in to start your session
                                            </h4>
                                        </div>
                                        <div>
                                            <form id="form-validation" name="form-validation" class="mb-4"
                                                action="{{ route('login') }}" method="POST">
                                                @csrf

                                                <div class="form-group mb-4">
                                                    <label class="mb-1 fw-bold">Email</label>
                                                    <input type="text" id="email" name="email"
                                                        data-validation="[NOTEMPTY]" class="form-control @error('email')
                                                            is-invalid
                                                        @enderror"
                                                        placeholder="Email" value="{{ old('email') }}" required autofocus>

                                                        @error('email')
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                </div>
                                                <div class="form-group mb-4">
                                                    <label class="mb-1 fw-bold">Password</label>
                                                    <div class="input-group"><input type="password"
                                                            id="password" name="password"
                                                            data-validation="[L&gt;=6]"
                                                            data-validation-message="$ must be at least 6 characters"
                                                            class="form-control" placeholder="Password"
                                                            value=""><input type="text" class="form-control"
                                                            placeholder="Password" style="display: none;">
                                                        {{-- <div class="input-group-append" style="cursor: pointer;">
                                                            <button tabindex="100"
                                                                title="Click here to show/hide password" class="btn"
                                                                type="button">
                                                                <i class="icon-eye-open  fe fe-eye">

                                                                </i></button>
                                                        </div> --}}
                                                    </div>
                                                </div>
                                                {{-- <div class="text-end mb-3">
                                                    <a href="https://nadia.injourneyairports.id/forgot-password"
                                                        class="link-primary fw-bold">
                                                        Forgot Password
                                                    </a>
                                                </div> --}}
                                                <button type="submit"
                                                    class="btn btn-primary text-center ladda-button w-100 py-3"
                                                    data-style="expand-right">
                                                    <span class="ladda-label">Log In</span>
                                                </button>
                                                {{-- <div class="text-center my-3">
                                                    <span class="text-muted">Or</span>
                                                </div> --}}

                                                {{-- <a href="https://nadia.injourneyairports.id/auth/google"
                                                    class="btn btn-outline-primary text-center w-100 py-3">
                                                    <span>
                                                        <img src="https://nadia.injourneyairports.id/assets/media/icon/icon-google-color.svg"
                                                            alt="Logo Google" width="25">
                                                    </span> Login with Google
                                                </a> --}}
                                            </form>
                                        </div>
                                        <div class="mt-auto pb-5 pt-5">
                                            <div class="text-center">
                                                Fire Extinguiser Check InJourney Airports (FECIA)
                                                <br>
                                                Copyright ©{{ now()->year }} PT Angkasa Pura Indonesia
                                                <br>All Rights Reserved
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <script src="https://nadia.injourneyairports.id/assets/vendors/bootstrap/bootstrap.bundle.min.js"></script> --}}




</body>


</html>
