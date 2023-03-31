<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Office extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'address',
        'latitude',
        'longitude',
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