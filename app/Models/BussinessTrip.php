<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BussinessTrip extends Model
{
    use HasFactory;
    protected $table = 'bussiness_trip';
    protected $fillable = [
        'employee_id',
        'office_id',
        'presence_id',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'file',
        'status',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'id'
    ];
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'nip');
    }
    public function office()
    {
        return $this->belongsTo(Office::class, 'office_id', 'id');
    }
    public function presence()
    {
        return $this->belongsTo(Presence::class, 'presence_id', 'id');
    }
}