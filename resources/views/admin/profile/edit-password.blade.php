@extends('admin.template')

@section('title', 'Change Password')

@section('breadcrumb')
  <h1 class="mt-4">
    Profile
  </h1>
  <ol class="breadcrumb mb-4">
    <li class="breadcrumb-item"><a href="{{ route('profile.show') }}" class="text-decoration-none text-secondary" >My Profile</a></li>
    <li class="breadcrumb-item" class="fw-normal text-dark">Edit Password</li>
  </ol>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <div class="d-flex justify-content-between">
              {{-- <h5 class="card-title">Edit Profile</h5> --}}
            <a href="{{ route('profile.show') }}" class="text-decoration-none text-secondary">
                <i class="fas fa-arrow-left"></i> Back to Profile
            </a>
            </div>
        </div>
            <div class="card-body p-1 bg-light">
                <div class="form-input">
                  <form action="{{ route('profile.update-password') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group mb-3">
                        <label for="current_password" class="form-label">Current Password *</label>
                        <input type="password" name="current_password" id="current_password" 
                               class="form-control @error('current_password') is-invalid @enderror" 
                               required>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="password" class="form-label">New Password *</label>
                        <input type="password" name="password" id="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">Minimum 8 characters</small>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="password_confirmation" class="form-label">Confirm New Password *</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" 
                               class="form-control" required>
                    </div>
                    
                    <div class="form-group mb-3 text-end">
                        <a href="{{ route('profile.show') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-key"></i> Change Password
                        </button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection