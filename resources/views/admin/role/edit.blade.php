@extends('admin.template')

@section('title', 'Edit Role')

@section('breadcrumb')
  <h1 class="mt-4">Roles</h1>
  <ol class="breadcrumb mb-4">
    <li class="breadcrumb-item" class="text-decoration-none"><a href="{{ route('role.index') }}" class="text-decoration-none text-secondary">Roles</a></li>
    <li class="breadcrumb-item fw-normal text-dark">Edit</li>
  </ol>
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card mb-4">
      <div class="card-body p-1 bg-light">
        <div class="header-content pt-1 ps-3 pe-3">
          <div class="d-flex justify-content-between">
            <a href="{{ route('role.index') }}" class="btn btn-sm bg-white btn-custom-hover" style="border: 1px solid rgba(0,0,0,0.15)"><i class="fa-solid fa-arrow-left"></i></a>

          </div>
        </div>

        <div class="mt-4">
          <div class="form-input">
            <form action="{{ route('role.update', $role->id) }}" method="post">
              @csrf
              @method('PUT')

              <div class="mb-3">
                <label for="name" class="form-label">Nama Lantai</label>
                <input type="text" name="name" id="name" class="form-control @error('name')
                  is-invalid
                @enderror" autofocus value="{{ $role->name }}">

                @error('name')
                  <small class="text-danger">{{ $message }}</small>
                @enderror
              </div>

              <div class="mb-3">
                <label for="guard_name" class="form-label">Guar Name</label>
                <input type="text" name="guard_name" id="guard_name" 
                                       class="form-control @error('guard_name') is-invalid @enderror" 
                                       value="{{ old('guard_name', $role->guard_name) }}" disabled>

                @error('guard_name')
                  <small class="text-danger">{{ $message }}</small>
                @enderror
              </div>

              <div class="mb-3">
                <div class="form-group">
                        <label>Permissions</label>
                        <div class="row">
                            @foreach($permissions as $permission)
                            <div class="col-md-3 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" 
                                           name="permissions[]" 
                                           value="{{ $permission->id }}" 
                                           id="perm_{{ $permission->id }}"
                                           {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="perm_{{ $permission->id }}">
                                        {{ $permission->name }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
              </div>

              <div class="text-end">
                <button type="reset" class="btn btn-sm btn-warning"><i class="fa-solid fa-brush"></i></button>
                <button type="submit" class="btn btn-sm btn-primary"><i class="fa-solid fa-floppy-disk"></i></button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


{{-- <div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit Role</h3>
                <a href="{{ route('role.index') }}" class="btn btn-secondary float-right">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('role.update', $role) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Role Name *</label>
                                <input type="text" name="name" id="name" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name', $role->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="guard_name">Guard Name</label>
                                <input type="text" name="guard_name" id="guard_name" 
                                       class="form-control @error('guard_name') is-invalid @enderror" 
                                       value="{{ old('guard_name', $role->guard_name) }}">
                                @error('guard_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Permissions</label>
                        <div class="row">
                            @foreach($permissions as $permission)
                            <div class="col-md-3 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" 
                                           name="permissions[]" 
                                           value="{{ $permission->id }}" 
                                           id="perm_{{ $permission->id }}"
                                           {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="perm_{{ $permission->id }}">
                                        {{ $permission->name }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Role
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> --}}
@endsection