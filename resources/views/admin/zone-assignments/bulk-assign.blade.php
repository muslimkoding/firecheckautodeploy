@extends('admin.template')

@section('title', 'Bulk Zone Assignment')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Bulk Zone Assignment</h5>
                <a href="{{ route('zone-assignments.index') }}" class="btn btn-secondary float-right">
                    <i class="fas fa-arrow-left"></i> Back to Assignments
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('zone-assignments.bulk-assign') }}" method="POST" id="bulkAssignForm">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="group_id" class="form-label">Select Group *</label>
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
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Current Assigned Zones</label>
                                <div id="currentZones" class="border rounded p-3 bg-light">
                                    <p class="text-muted mb-0">Select a group to see current assignments</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Select Zones to Assign *</label>
                        <div class="row">
                            @foreach($zones as $zone)
                            <div class="col-md-3 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input zone-checkbox" type="checkbox" 
                                           name="zone_ids[]" 
                                           value="{{ $zone->id }}" 
                                           id="zone_{{ $zone->id }}">
                                    <label class="form-check-label" for="zone_{{ $zone->id }}">
                                        {{ $zone->name }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @error('zone_ids')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle"></i> Information</h6>
                        <p class="mb-0">This will replace all existing zone assignments for the selected group with the zones you select above.</p>
                    </div>
                    
                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-layer-group"></i> Assign Zones
                        </button>
                        <a href="{{ route('zone-assignments.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const groupSelect = document.getElementById('group_id');
    const currentZonesDiv = document.getElementById('currentZones');
    
    groupSelect.addEventListener('change', function() {
        const groupId = this.value;
        
        if (!groupId) {
            currentZonesDiv.innerHTML = '<p class="text-muted mb-0">Select a group to see current assignments</p>';
            return;
        }
        
        // Show loading
        currentZonesDiv.innerHTML = '<p class="text-muted mb-0">Loading...</p>';
        
        // Fetch assigned zones
        fetch(`/admin/zone-assignments/${groupId}/zones`)
            .then(response => response.json())
            .then(zones => {
                if (zones.length > 0) {
                    let html = '<strong>Currently Assigned Zones:</strong><br>';
                    zones.forEach(zone => {
                        html += `<span class="badge bg-primary me-1 mb-1">${zone.name}</span>`;
                    });
                    currentZonesDiv.innerHTML = html;
                    
                    // Auto-check currently assigned zones
                    document.querySelectorAll('.zone-checkbox').forEach(checkbox => {
                        const zoneId = parseInt(checkbox.value);
                        const isAssigned = zones.some(zone => zone.id === zoneId);
                        checkbox.checked = isAssigned;
                    });
                } else {
                    currentZonesDiv.innerHTML = '<p class="text-muted mb-0">No zones currently assigned to this group.</p>';
                    
                    // Uncheck all if no assignments
                    document.querySelectorAll('.zone-checkbox').forEach(checkbox => {
                        checkbox.checked = false;
                    });
                }
            })
            .catch(error => {
                currentZonesDiv.innerHTML = '<p class="text-danger mb-0">Error loading zones</p>';
                console.error('Error:', error);
            });
    });
});
</script>
@endsection