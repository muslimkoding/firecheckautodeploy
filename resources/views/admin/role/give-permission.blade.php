@extends('admin.template')

@section('title', 'Manage Permissions for Role')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Manage Permissions for Role: <strong>{{ $role->name }}</strong></h3>
                <a href="{{ route('role.index') }}" class="btn btn-secondary float-right">
                    <i class="fas fa-arrow-left"></i> Back to Roles
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('roles.update-permissions', $role) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="alert alert-info">
                        <h5><i class="fas fa-info-circle"></i> Information</h5>
                        <p class="mb-0">Select the permissions you want to assign to this role. The role will have access to all selected permissions.</p>
                    </div>

                    <div class="form-group">
                        <label>Select Permissions</label>
                        <div class="row">
                            @foreach($permissions as $permission)
                            <div class="col-md-3 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="permissions[]" 
                                                   value="{{ $permission->id }}" 
                                                   id="perm_{{ $permission->id }}"
                                                   {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                <strong>{{ $permission->name }}</strong>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Update Permissions
                        </button>
                        <a href="{{ route('role.show', $role) }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection