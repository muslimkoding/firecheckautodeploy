@extends('admin.template')

@section('title', 'Zone Assignments')

@section('breadcrumb')
  <h1 class="mt-4">Jadwal Pengecekan</h1>
  <ol class="breadcrumb mb-4">
    <li class="breadcrumb-item">Config Jadwal</li>
  </ol>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title">Zone Assignments Management</h3>
                <div>
                    @hasanyrole('superadmin')
                    <a href="{{ route('zone-assignments.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Add Assignment
                    </a>
                    @endhasanyrole
                    {{-- <a href="{{ route('zone-assignments.bulk-assign') }}" class="btn btn-sm btn-success">
                        <i class="fas fa-layer-group"></i> Bulk Assign
                    </a> --}}
                </div>
            </div>
            <div class="card-body p-1 bg-light">
                @if($zoneAssignments->count() > 0)
                    @foreach($zoneAssignments as $zoneName => $assignments)
                    <div class="card mb-2">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">
                                <i class="fas fa-map-marker-alt"></i> 
                                Zone: <strong>{{ $zoneName }}</strong>
                                <span class="badge bg-primary">{{ $assignments->count() }} Groups</span>
                            </h5>
                        </div>
                        <div class="card-body p-1 bg-white">
                            <div class="row p-2">
                                @foreach($assignments as $assignment)
                                <div class="col-md-12 mb-3">
                                    <div class="card p-2">
                                        <div class="card-body p-1 bg-white d-flex justify-content-between">
                                            <div class="">
                                                <h6 class="card-title">
                                                    <i class="fas fa-users"></i> 
                                                    {{ $assignment->group->name }}
                                                </h6>
                                                <p class="card-text text-muted small">
                                                    Assigned: {{ $assignment->created_at->format('d M Y') }}
                                                </p>
                                            </div>
                                            <div class="">
                                               
                                                    {{-- <a href="{{ route('zone-assignments.show', $assignment) }}" 
                                                       class="btn btn-info" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a> --}}
                                                    <a href="{{ route('zone-assignments.edit', $assignment) }}" 
                                                       class="btn btn-sm btn-light border" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    @hasanyrole('superadmin')
                                                    <form action="{{ route('zone-assignments.destroy', $assignment) }}" 
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Are you sure?')">
                                                  @csrf
                                                  @method('DELETE')
                                                  <button type="submit" class="btn btn-sm btn-light border" title="Delete">
                                                      <i class="fas fa-trash text-danger"></i>
                                                  </button>
                                              </form>
                                                    @endhasanyrole
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="card border">
                      <div class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h4>No Zone Assignments</h4>
                        <p class="text-muted">No zones have been assigned to groups yet.</p>
                        @hasanyrole('superadmin')
                        <a href="{{ route('zone-assignments.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Create First Assignment
                        </a>
                        @endhasanyrole
                    </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection