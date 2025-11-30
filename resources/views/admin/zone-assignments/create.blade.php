@extends('admin.template')

@section('title', 'Create Zone Assignment')

@section('breadcrumb')
  <h1 class="mt-4">
    Zone Assignment
  </h1>
  <ol class="breadcrumb mb-4">
    <li class="breadcrumb-item"><a href="{{ route('zone-assignments.index') }}" class="text-decoration-none text-secondary" >Config Jadwal</a></li>
    <li class="breadcrumb-item" class="fw-normal text-dark">Buat</li>
  </ol>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                {{-- <h5 class="card-title">Create Zone Assignment</h5> --}}
                <a href="{{ route('zone-assignments.index') }}" class="text-decoration-none text-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
            <div class="card-body p-1 bg-light">
                <div class="form-input">
                  <form action="{{ route('zone-assignments.store') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label for="zone_id" class="form-label">Zone *</label>
                        <select name="zone_id" id="zone_id" 
                                class="form-control @error('zone_id') is-invalid @enderror" required>
                            <option value="">Select Zone</option>
                            @foreach($zones as $zone)
                                <option value="{{ $zone->id }}" {{ old('zone_id') == $zone->id ? 'selected' : '' }}>
                                    {{ $zone->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('zone_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="group_id" class="form-label">Group *</label>
                        <select name="group_id" id="group_id" 
                                class="form-control @error('group_id') is-invalid @enderror" required>
                            <option value="">Select Group</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}" {{ old('group_id') == $group->id ? 'selected' : '' }}>
                                    {{ $group->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('group_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group mt-4 text-end">
                        <a href="{{ route('zone-assignments.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Create Assignment
                        </button>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection