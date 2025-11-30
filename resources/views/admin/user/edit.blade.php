@extends('admin.template')

@section('title', 'Edit User')

@section('breadcrumb')
    <h1 class="mt-4">Manajemen User</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('user.index') }}" class="text-decoration-none text-secondary">List User</a></li>
        <li class="breadcrumb-item fw-normal text-dark">Edit</li>
    </ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('user.index') }}" class="btn btn-sm bg-white btn-custom-hover" style="border: 1px solid rgba(0,0,0,0.15)">
                            <i class="fa-solid fa-arrow-left"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body p-1 bg-light">
                    <div class="form-input">
                        <form action="{{ route('user.update', $user->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- Kolom Kiri -->
                                <div class="col-md-6">
                                    <!-- Current Avatar -->
                                    <div class="mb-3 text-center">
                                        <label class="form-label d-block">Foto Profil Saat Ini</label>
                                        @if($user->image)
                                            <img src="{{ asset('storage/' . $user->image) }}" 
                                                 alt="{{ $user->name }}" 
                                                 class="rounded-circle mb-2" 
                                                 width="120" height="120"
                                                 style="object-fit: cover;">
                                            <br>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="remove_image" id="remove_image" value="1">
                                                <label class="form-check-label text-danger" for="remove_image">
                                                    Hapus foto profil
                                                </label>
                                            </div>
                                        @else
                                            <div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center" 
                                                 style="width: 120px; height: 120px;">
                                                <i class="fas fa-user text-white fa-3x"></i>
                                            </div>
                                            <p class="text-muted mt-2">Tidak ada foto profil</p>
                                        @endif
                                    </div>

                                    <!-- Nama -->
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text" name="name" id="name" 
                                               class="form-control @error('name') is-invalid @enderror" 
                                               value="{{ old('name', $user->name) }}" required autofocus>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Email -->
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" name="email" id="email" 
                                               class="form-control @error('email') is-invalid @enderror" 
                                               value="{{ old('email', $user->email) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Password -->
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password Baru</label>
                                        <input type="password" name="password" id="password" 
                                               class="form-control @error('password') is-invalid @enderror" 
                                               placeholder="Kosongkan jika tidak ingin mengubah">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">Minimal 8 karakter</div>
                                    </div>

                                    <!-- Confirm Password -->
                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation" 
                                               class="form-control @error('password') is-invalid @enderror" 
                                               placeholder="Kosongkan jika tidak ingin mengubah">
                                    </div>

                                    <div class="mb-3">
                                        <label for="roles" class="form-label">roles</label>
                                        <select name="roles[]" id="roles" class="form-control @error('roles')
                                          is-invalid
                                        @enderror" multiple>
                                      {{-- <option value="" hidden>Pilih</option> --}}
                                      @foreach ($roles as $item)
                                        <option value="{{ $item }}" {{ in_array($item, $userRoles) ? 'selected' : '' }}>{{ $item }}</option>
                                      @endforeach
                                      </select>
                          
                                        @error('roles')
                                          <div class="alert alert-danger mt-2">
                                            {{ $message }}
                                          </div>
                                        @enderror
                                      </div>
                                </div>

                                <!-- Kolom Kanan -->
                                <div class="col-md-6">
                                    <!-- NIP -->
                                    <div class="mb-3">
                                        <label for="nip" class="form-label">NIP</label>
                                        <input type="text" name="nip" id="nip" 
                                               class="form-control @error('nip') is-invalid @enderror" 
                                               value="{{ old('nip', $user->nip) }}">
                                        @error('nip')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Tanggal Lahir -->
                                    <div class="mb-3">
                                        <label for="date_birth" class="form-label">Tanggal Lahir</label>
                                        <input type="date" name="date_birth" id="date_birth" 
                                               class="form-control @error('date_birth') is-invalid @enderror" 
                                               value="{{ old('date_birth', $user->date_birth ? $user->date_birth->format('Y-m-d') : '') }}">
                                        @error('date_birth')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Tipe Pegawai -->
                                    <div class="mb-3">
                                        <label for="employe_type_id" class="form-label">Tipe Pegawai</label>
                                        <select name="employe_type_id" id="employe_type_id" 
                                                class="form-select @error('employe_type_id') is-invalid @enderror">
                                            <option value="">Pilih Tipe Pegawai</option>
                                            @foreach($employeeTypes as $type)
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

                                    <!-- Group -->
                                    <div class="mb-3">
                                        <label for="group_id" class="form-label">Group</label>
                                        <select name="group_id" id="group_id" 
                                                class="form-select @error('group_id') is-invalid @enderror">
                                            <option value="">Pilih Group</option>
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

                                    <!-- Posisi -->
                                    <div class="mb-3">
                                        <label for="position_id" class="form-label">Posisi</label>
                                        <select name="position_id" id="position_id" 
                                                class="form-select @error('position_id') is-invalid @enderror">
                                            <option value="">Pilih Posisi</option>
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

                                    <!-- Kompetensi -->
                                    <div class="mb-3">
                                        <label for="competency_id" class="form-label">Kompetensi</label>
                                        <select name="competency_id" id="competency_id" 
                                                class="form-select @error('competency_id') is-invalid @enderror">
                                            <option value="">Pilih Kompetensi</option>
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

                                    <!-- Foto Profil Baru -->
                                    <div class="mb-3">
                                        <label for="image" class="form-label">Ganti Foto Profil</label>
                                        <input type="file" name="image" id="image" 
                                               class="form-control @error('image') is-invalid @enderror" 
                                               accept="image/jpeg,image/png,image/jpg,image/gif">
                                        <div class="form-text">
                                            Format: JPEG, PNG, JPG, GIF. Maksimal: 2MB
                                        </div>
                                        @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        
                                        <!-- Image Preview -->
                                        <div id="image-preview" class="mt-2" style="display: none;">
                                            <img id="preview" src="#" alt="Preview" class="img-thumbnail" style="max-width: 150px; max-height: 150px;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Last Updated Info -->
                            {{-- <div class="mt-3">
                                <small class="text-muted">Terakhir dibuat : 
                                    <strong>{{ $lastUser ? $lastUser->name . ' (' . $lastUser->email . ')' : 'Belum ada data' }}</strong>
                                </small>
                                <br>
                                <small class="text-muted">User ini dibuat pada: 
                                    <strong>{{ $user->created_at->format('d-m-Y H:i') }}</strong>
                                </small>
                                @if($user->updated_at != $user->created_at)
                                    <br>
                                    <small class="text-muted">Terakhir diupdate: 
                                        <strong>{{ $user->updated_at->format('d-m-Y H:i') }}</strong>
                                    </small>
                                @endif
                            </div> --}}

                            <!-- Action Buttons -->
                            <div class="text-end mt-4">
                                <button type="reset" class="btn btn-sm btn-light" style="border: 1px solid rgba(0,0,0,0.15)">
                                    <i class="fa-solid fa-brush"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-sm btn-primary">
                                    <i class="fa-solid fa-floppy-disk"></i> Update
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Image preview functionality
    document.getElementById('image').addEventListener('change', function(e) {
        const preview = document.getElementById('preview');
        const previewContainer = document.getElementById('image-preview');
        const file = e.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                previewContainer.style.display = 'block';
            }
            
            reader.readAsDataURL(file);
        } else {
            previewContainer.style.display = 'none';
        }
    });

    // Password strength indicator
    document.getElementById('password').addEventListener('input', function() {
        const password = this.value;
        
        if (password.length === 0) {
            // Remove indicator if password is empty
            let existingIndicator = this.parentNode.querySelector('.password-strength');
            if (existingIndicator) {
                existingIndicator.remove();
            }
            return;
        }
        
        const strengthText = document.createElement('div');
        strengthText.className = 'form-text';
        
        if (password.length < 8) {
            strengthText.textContent = 'Password terlalu pendek (minimal 8 karakter)';
            strengthText.className = 'form-text text-danger';
        } else if (password.length < 12) {
            strengthText.textContent = 'Kekuatan password: Sedang';
            strengthText.className = 'form-text text-warning';
        } else {
            strengthText.textContent = 'Kekuatan password: Kuat';
            strengthText.className = 'form-text text-success';
        }
        
        // Update or create strength indicator
        let existingIndicator = this.parentNode.querySelector('.password-strength');
        if (existingIndicator) {
            existingIndicator.remove();
        }
        strengthText.classList.add('password-strength');
        this.parentNode.appendChild(strengthText);
    });

    // Confirm before removing image
    document.getElementById('remove_image').addEventListener('change', function() {
        if (this.checked) {
            if (!confirm('Yakin ingin menghapus foto profil?')) {
                this.checked = false;
            }
        }
    });
</script>
@endpush