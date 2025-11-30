<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - APAR Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .register-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #ffffff 0%, #ffffff 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }
        .register-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 450px;
        }
        .register-header {
            background: linear-gradient(135deg, #acacac 0%, #acacac 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .register-body {
            padding: 2rem;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-register {
            background: linear-gradient(135deg, #133eff 0%, #133eff 100%);
            border: none;
            color: white;
            padding: 0.75rem;
            font-weight: 600;
        }
        .btn-register:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
            color: white;
        }
        .password-strength {
            font-size: 0.8rem;
            margin-top: 0.25rem;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <div class="register-header">
                <h2><i class="fas fa-fire-extinguisher me-2"></i>APAR System</h2>
                <p class="mb-0">Create your account</p>
            </div>
            <div class="register-body">
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

                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" 
                                   placeholder="Enter your full name" required autofocus>
                        </div>
                        @error('name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" 
                                   placeholder="Enter your email" required>
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
                        <div class="password-strength text-muted">
                            Password must be at least 8 characters long
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation" 
                                   placeholder="Confirm your password" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-register w-100 mb-3">
                        <i class="fas fa-user-plus me-2"></i>Create Account
                    </button>

                    <div class="text-center">
                        <p class="mb-0">Already have an account? 
                            <a href="{{ route('login') }}" class="text-decoration-none">Sign in</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Password strength indicator (optional)
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthText = document.querySelector('.password-strength');
            
            if (password.length === 0) {
                strengthText.textContent = 'Password must be at least 8 characters long';
                strengthText.className = 'password-strength text-muted';
            } else if (password.length < 8) {
                strengthText.textContent = 'Password too short';
                strengthText.className = 'password-strength text-danger';
            } else if (password.length < 12) {
                strengthText.textContent = 'Good password';
                strengthText.className = 'password-strength text-warning';
            } else {
                strengthText.textContent = 'Strong password';
                strengthText.className = 'password-strength text-success';
            }
        });
    </script>
    <script src="{{ asset('assets/sweetalert.js') }}"></script>
</body>
</html>