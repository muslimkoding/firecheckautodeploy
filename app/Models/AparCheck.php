<?php

namespace App\Models;

use App\Models\Apar;
use App\Models\AparCylinder;
use App\Models\AparHandle;
use App\Models\AparHose;
use App\Models\AparPinSeal;
use App\Models\AparPressure;
use App\Models\Building;
use App\Models\ExtinguisherCondition;
use App\Models\Group;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class AparCheck extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'apar_id',
        'group_id',
        'date_check',
        'zone_id',
        'building_id',
        'location',
        'apar_pressure_id',
        'apar_cylinder_id',
        'apar_pin_seal_id',
        'apar_hose_id',
        'apar_handle_id',
        'extinguisher_condition_id',
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

    public function apar()
    {
        return $this->belongsTo(Apar::class);
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

    public function pressure()
    {
        return $this->belongsTo(AparPressure::class, 'apar_pressure_id');
    }

    public function cylinder()
    {
        return $this->belongsTo(AparCylinder::class, 'apar_cylinder_id');
    }

    public function pinSeal()
    {
        return $this->belongsTo(AparPinSeal::class, 'apar_pin_seal_id');
    }

    public function hose()
    {
        return $this->belongsTo(AparHose::class, 'apar_hose_id');
    }

    public function handle()
    {
        return $this->belongsTo(AparHandle::class, 'apar_handle_id');
    }

    public function condition()
    {
        return $this->belongsTo(ExtinguisherCondition::class, 'extinguisher_condition_id');
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
            case '5':
                $badge = '<span class="badge bg-warning">Perlu Perbaikan</span>';
                break;
            case '4':
                $badge = '<span class="badge bg-danger">Perlu Diganti</span>';
                break;
            default:
                $badge = '<span class="badge bg-secondary">-</span>';
        }

        return $badge;
    }
}
