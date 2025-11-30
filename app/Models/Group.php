<?php

namespace App\Models;

use App\Models\User;
use App\Models\Zone;
use App\Models\ZoneAssignment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
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
     * Relasi many-to-many ke Zone melalui ZoneAssignment
     */
    public function zones()
    {
        return $this->belongsToMany(Zone::class, 'zone_assignments')
                    ->withTimestamps();
    }

    /**
     * Relasi ke User
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Cek apakah group memiliki akses ke zone tertentu
     */
    public function hasAccessToZone($zoneId)
    {
        return $this->zones()->where('zone_id', $zoneId)->exists();
    }

    /**
     * Dapatkan daftar zone IDs yang diassign ke group
     */
    public function getAssignedZoneIds()
    {
        return $this->zones()->pluck('zones.id')->toArray();
    }
}
