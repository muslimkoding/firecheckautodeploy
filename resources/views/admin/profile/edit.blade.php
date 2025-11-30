@extends('admin.template')

@section('title', 'Edit Profile')

@section('breadcrumb')
  <h1 class="mt-4">
    Profile
  </h1>
  <ol class="breadcrumb mb-4">
    <li class="breadcrumb-item"><a href="{{ route('profile.show') }}" class="text-decoration-none text-secondary" >My Profile</a></li>
    <li class="breadcrumb-item" class="fw-normal text-dark">Edit</li>
  </ol>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
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
                  <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <div class="mb-3">
                                @if($user->image)
                                    <img src="{{ asset('storage/' . $user->image) }}" 
                                         alt="{{ $user->name }}" 
                                         class="img-fluid rounded mb-3" 
                                         style="max-height: 200px;">
                                @else
                                    <div class="bg-secondary rounded d-flex align-items-center justify-content-center mb-3" 
                                         style="height: 200px;">
                                        <i class="fas fa-user fa-3x text-white"></i>
                                    </div>
                                @endif
                                
                                <div class="mb-3">
                                    <label for="image" class="form-label">Profile Picture</label>
                                    <input type="file" class="form-control" id="image" name="image" 
                                           accept="image/jpeg,image/png,image/jpg,image/gif">
                                    <small class="form-text text-muted">Max size: 2MB. Formats: JPEG, PNG, JPG, GIF</small>
                                </div>
                                
                                @if($user->image)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remove_image" id="remove_image">
                                    <label class="form-check-label text-danger" for="remove_image">
                                        Remove current photo
                                    </label>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="name" class="form-label">Full Name *</label>
                                        <input type="text" name="name" id="name" 
                                               class="form-control @error('name') is-invalid @enderror" 
                                               value="{{ old('name', $user->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="email" class="form-label">Email *</label>
                                        <input type="email" name="email" id="email" 
                                               class="form-control @error('email') is-invalid @enderror" 
                                               value="{{ old('email', $user->email) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="nip" class="form-label">NIP</label>
                                        <input type="text" name="nip" id="nip" 
                                               class="form-control @error('nip') is-invalid @enderror" 
                                               value="{{ old('nip', $user->nip) }}">
                                        @error('nip')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="date_birth" class="form-label">Date of Birth</label>
                                        <input type="date" name="date_birth" id="date_birth" 
                                               class="form-control @error('date_birth') is-invalid @enderror" 
                                               value="{{ old('date_birth', $user->date_birth) }}">
                                        @error('date_birth')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Jika ada relasi lainnya, bisa ditambahkan di sini -->
                            @if(isset($employeTypes))
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="employe_type_id" class="form-label">Employee Type</label>
                                        <select name="employe_type_id" id="employe_type_id" 
                                                class="form-control @error('employe_type_id') is-invalid @enderror">
                                            <option value="">Select Employee Type</option>
                                            @foreach($employeTypes as $type)
                                                <option value="{{ $type->id }}" 
                                                    {{ old('employe_type_id', $user->employe_type_id) == $type->id ? 'selected' : '' }}>
                                                    {{ $type->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('employe_type_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="group_id" class="form-label">Group</label>
                                        <select name="group_id" id="group_id" 
                                                class="form-control @error('group_id') is-invalid @enderror">
                                            <option value="">Select Group</option>
                                            @foreach($groups as $group)
                                                <option value="{{ $group->id }}" 
                                                    {{ old('group_id', $user->group_id) == $group->id ? 'selected' : '' }}>
                                                    {{ $group->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('group_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="position_id" class="form-label">Position</label>
                                        <select name="position_id" id="position_id" 
                                                class="form-control @error('position_id') is-invalid @enderror">
                                            <option value="">Select Position</option>
                                            @foreach($positions as $position)
                                                <option value="{{ $position->id }}" 
                                                    {{ old('position_id', $user->position_id) == $position->id ? 'selected' : '' }}>
                                                    {{ $position->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('position_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="competency_id" class="form-label">Competency</label>
                                        <select name="competency_id" id="competency_id" 
                                                class="form-control @error('competency_id') is-invalid @enderror">
                                            <option value="">Select Competency</option>
                                            @foreach($competencies as $competency)
                                                <option value="{{ $competency->id }}" 
                                                    {{ old('competency_id', $user->competency_id) == $competency->id ? 'selected' : '' }}>
                                                    {{ $competency->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('competency_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="form-group mb-3 text-end">
                      <a href="{{ route('profile.show') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Profile
                        </button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection