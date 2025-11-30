@extends('admin.template')

@section('title', 'Permission Details')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Permission Details</h3>
                <a href="{{ route('permission.index') }}" class="btn btn-secondary float-right">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">ID</th>
                                <td>{{ $permission->id }}</td>
                            </tr>
                            <tr>
                                <th>Name</th>
                                <td>{{ $permission->name }}</td>
                            </tr>
                            <tr>
                                <th>Guard Name</th>
                                <td>{{ $permission->guard_name }}</td>
                            </tr>
                            <tr>
                                <th>Created At</th>
                                <td>{{ $permission->created_at->format('d M Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Updated At</th>
                                <td>{{ $permission->updated_at->format('d M Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5>Roles with this Permission</h5>
                        @if($roles->count() > 0)
                            <ul class="list-group">
                                @foreach($roles as $role)
                                <li class="list-group-item">
                                    <a href="{{ route('role.show', $role) }}">{{ $role->name }}</a>
                                </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted">No roles have this permission.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection