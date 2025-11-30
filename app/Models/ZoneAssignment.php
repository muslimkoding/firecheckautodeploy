<?php

namespace App\Models;

use App\Models\Group;
use App\Models\Zone;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZoneAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'zone_id',
        'group_id'
    ];

    /**
     * Relasi ke model Zone
     */
    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    /**
     * Relasi ke model Group
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
