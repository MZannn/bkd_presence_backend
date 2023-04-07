<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class Employee extends Model
{
    use HasApiTokens, HasFactory, SoftDeletes;

    protected $fillable = [
        'nip',
        'name',
        'password',
        'position',
        'phone_number',
        'profile_photo_path',
        'office_id',
    ];
    
    protected $table = 'employees';
    protected $hidden = [
        'password',
        'remember_token',
        'deleted_at',
        'created_at',
        'updated_at'
    ];
    protected $primaryKey = 'nip';
    protected $dates = ['deleted_at'];
    public function office()
    {
        return $this->belongsTo(Office::class, 'office_id', 'id');
    }
    public function presences()
    {
        return $this->hasMany(Presence::class, 'employee_id', 'nip');
    }
}