<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtinguisherCondition extends Model
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
}
