<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'latitude',
        'longitude',
        'radius',
        'start_work',
        'start_break',
        'late_tolerance',
        'end_work',
    ];

    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at'
    ];
    public function employees()
    {
        return $this->hasMany(Employee::class, 'office_id', 'id');
    }
    public function presences()
    {
        return $this->hasMany(Presence::class, 'office_id', 'id');
    }
    public function users()
    {
        return $this->hasMany(User::class, 'office_id', 'id');
    }
}