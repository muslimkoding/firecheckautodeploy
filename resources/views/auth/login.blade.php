<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - APAR Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .login-container {
            min-height: 100vh;
            /* background: linear-gradient(135deg, #ffffff 0%, #ffffff 100%); */
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: white;
            border-radius: 15px;
            /* box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1); */
            border: 1px solid #60AEBD;
            overflow: hidden;
            width: 100%;
            max-width: 400px;
        }
        .login-header {
            /* background: linear-gradient(135deg, #acacac 0%, #acacac 100%); */
            background: #60AEBD;
            /* color: white; */
            padding: 2rem;
            text-align: center;
        }
        .login-body {
            padding: 2rem;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-login {
            background: #60AEBD;
            border: none;
            color: white;
            padding: 0.75rem;
            font-weight: 600;
        }
        .btn-login:hover {
            background: #419fb2;
            color: white;
        }
        .remember-me label {
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header text-white">
                <h2><i class="fas fa-fire-extinguisher me-2"></i>APAR System</h2>
                <p class="mb-0">Sign in to your account</p>
            </div>
            <div class="login-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" 
                                   placeholder="Enter your email" required autofocus>
                        </div>
                        @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" placeholder="Enter your password" required>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3 remember-me">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" 
                                   {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                Remember Me
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-login w-100 mb-3">
                        <i class="fas fa-sign-in-alt me-2"></i>Sign In
                    </button>

                    <div class="text-center">
                        <p class="mb-0">Don't have an account? 
                            <a href="{{ route('register') }}" class="text-decoration-none">Sign up</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/sweetalert.js') }}"></script>
</body>
</html>