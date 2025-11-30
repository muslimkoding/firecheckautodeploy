<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Group;
use App\Models\Position;
use App\Models\AparCheck;
use App\Models\Competency;
use App\Models\EmployeeType;
use App\Models\HydrantCheck;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'date_birth',
        'employe_type_id',
        'group_id',
        'position_id',
        'competency_id',
        'nip',
        'image'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_birth' => 'date',
        ];
    }

    // Relationships
    public function employeeType()
    {
        return $this->belongsTo(EmployeeType::class, 'employe_type_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function competency()
    {
        return $this->belongsTo(Competency::class);
    }

    // Relasi ke AparChecks (jika ada)
    public function aparChecks()
    {
        // return $this->hasMany(AparCheck::class);
        return $this->hasMany(AparCheck::class, 'user_id', 'id');
    }

    // Relasi ke HydrantChecks (jika ada)
    public function hydrantChecks()
    {
        // return $this->hasMany(HydrantCheck::class);
        return $this->hasMany(HydrantCheck::class, 'user_id', 'id');
    }

    // Accessors
    public function getFormattedDateBirthAttribute()
    {
        return $this->date_birth?->format('d-m-Y');
    }

    public function getAgeAttribute()
    {
        return $this->date_birth?->age;
    }

    /**
     * Helper method untuk menentukan warna badge berdasarkan nama role
     */
    private function getRoleBadgeColor($roleName)
    {
        $colors = [
            'super-admin' => 'bg-danger',
            'admin' => 'bg-primary',
            'manager' => 'bg-info',
            'user' => 'bg-success',
            'staff' => 'bg-warning',
            'guest' => 'bg-secondary'
        ];

        return $colors[strtolower($roleName)] ?? 'bg-dark';
    }
}
