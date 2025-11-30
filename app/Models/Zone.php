<?php

namespace App\Models;

use App\Models\Apar;
use App\Models\Group;
use App\Models\Hydrant;
use App\Models\ZoneAssignment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description'
    ];

    protected $casts = [
        'name' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'

    ];

    /**
     * Relasi ke ZoneAssignment
     */
    public function zoneAssignments()
    {
        return $this->hasMany(ZoneAssignment::class);
    }

    /**
     * Relasi many-to-many ke Group melalui ZoneAssignment
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'zone_assignments')
                    ->withTimestamps();
    }

    /**
     * Relasi ke Apar
     */
    public function apars()
    {
        return $this->hasMany(Apar::class);
    }

    /**
     * Relasi ke Hydrant
     */
    public function hydrants()
    {
        return $this->hasMany(Hydrant::class);
    }
}
