@extends('admin.template')

@section('title', 'My Profile')

@section('breadcrumb')
  <h1 class="mt-4">Profile</h1>
  <ol class="breadcrumb mb-4">
    <li class="breadcrumb-item">{{ Auth::user()->name }}</li>
  </ol>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
              <div class="">Poto Profile</div>
            </div>
            <div class="card-body p-1 bg-light">
              <div class="card-body text-center rounded-3 bg-white border border-gray">
                @if($user->image)
                    <img src="{{ asset('storage/' . $user->image) }}" 
                         alt="{{ $user->name }}" 
                         class="img-fluid rounded-circle mb-3" 
                         style="width: 150px; height: 150px; object-fit: cover;">
                @else
                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                         style="width: 150px; height: 150px;">
                        <i class="fas fa-user fa-3x text-white"></i>
                    </div>
                @endif
                
                <h4>{{ $user->name }}</h4>
                <p class="text-muted">{{ $user->email }}</p>
                
                <div class="d-grid gap-2">
                    <a href="{{ route('profile.edit') }}" class="btn btn-secondary">
                        <i class="fas fa-edit"></i> Edit Profile
                    </a>
                    <a href="{{ route('profile.edit-password') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-key"></i> Change Password
                    </a>
                </div>
            </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Profile Information</h5>
            </div>
            <div class="card-body p-1 bg-light">
                <div class="table-responsive">
                    <table class="table border mb-0 align-middle bg-white">
                        <tr>
                            <th width="30%">Full Name</th>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th>NIP</th>
                            <td>
                                @if($user->nip)
                                    {{ $user->nip }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Date of Birth</th>
                            <td>
                                @if($user->date_birth)
                                    {{ \Carbon\Carbon::parse($user->date_birth)->format('d F Y') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Employee Type</th>
                            <td>
                                @if($user->employeType)
                                    {{ $user->employeType->name }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Group</th>
                            <td>
                                @if($user->group)
                                    {{ $user->group->name }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Position</th>
                            <td>
                                @if($user->position)
                                    {{ $user->position->name }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Competency</th>
                            <td>
                                @if($user->competency)
                                    {{ $user->competency->name }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Role</th>
                            <td>
                                @if($user->roles->count() > 0)
                                    @foreach($user->roles as $role)
                                        <span class="badge bg-primary">{{ $role->name }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">No role assigned</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Member Since</th>
                            <td>{{ $user->created_at->format('d F Y') }}</td>
                        </tr>
                        <tr>
                            <th>Last Updated</th>
                            <td>{{ $user->updated_at->format('d F Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection