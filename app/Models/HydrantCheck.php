<?php

namespace App\Models;

use App\Models\Building;
use App\Models\ExtinguisherCondition;
use App\Models\Group;
use App\Models\Hydrant;
use App\Models\HydrantCoupling;
use App\Models\HydrantDoor;
use App\Models\HydrantGuide;
use App\Models\HydrantHose;
use App\Models\HydrantMainValve;
use App\Models\HydrantNozzle;
use App\Models\HydrantSafetyMarking;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class HydrantCheck extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'hydrant_id', 
        'group_id', 
        'date_check', 
        'zone_id', 
        'building_id', 
        'location', 
        'extinguisher_condition_id', 
        'hydrant_door_id', 
        'hydrant_coupling_id', 
        'hydrant_main_valve_id', 
        'hydrant_hose_id', 
        'hydrant_nozzle_id', 
        'hydrant_safety_marking_id', 
        'hydrant_guide_id', 
        'hydrant_type_id', 
        'notes'
    ];

    protected $casts = [
        'date_check' => 'date'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hydrant()
    {
        return $this->belongsTo(Hydrant::class);
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function condition()
    {
        return $this->belongsTo(ExtinguisherCondition::class, 'extinguisher_condition_id');
    }

    public function hydrantDoor()
    {
        return $this->belongsTo(HydrantDoor::class, 'hydrant_door_id');
    }

    public function hydrantCoupling()
    {
        return $this->belongsTo(HydrantCoupling::class, 'hydrant_coupling_id');
    }

    public function hydrantMainValve()
    {
        return $this->belongsTo(HydrantMainValve::class, 'hydrant_main_valve_id');
    }

    public function hydrantHose()
    {
        return $this->belongsTo(HydrantHose::class, 'hydrant_hose_id');
    }

    public function hydrantNozzle()
    {
        return $this->belongsTo(HydrantNozzle::class, 'hydrant_nozzle_id');
    }

    public function hydrantSafetyMarking()
    {
        return $this->belongsTo(HydrantSafetyMarking::class, 'hydrant_safety_marking_id');
    }

    public function hydrantGuide()
    {
        return $this->belongsTo(HydrantGuide::class, 'hydrant_guide_id');
    }

    public function hydrantType()
    {
        return $this->belongsTo(HydrantType::class, 'hydrant_type_id');
    }

    // Accessors untuk formatting
    public function getFormattedCheckDateAttribute()
    {
        return $this->date_check ? \Carbon\Carbon::parse($this->date_check)->format('d M Y') : '-';
    }

    public function getStatusBadgeAttribute()
    {
        $status = $this->extinguisher_condition_id;
        $badge = '';

        switch ($status) {
            case '3':
                $badge = '<span class="badge bg-success">Siap Digunakan</span>';
                break;
            case '4':
                $badge = '<span class="badge bg-warning">Perlu Perbaikan</span>';
                break;
            case '5':
                $badge = '<span class="badge bg-danger">Perlu Diganti</span>';
                break;
            default:
                $badge = '<span class="badge bg-secondary">-</span>';
        }

        return $badge;
    }
}
