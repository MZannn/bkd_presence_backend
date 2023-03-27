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
}