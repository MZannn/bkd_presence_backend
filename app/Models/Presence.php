<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Presence extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'nip',
        'office_id',
        'attendance_clock',
        'attendance_clock_out',
        'presence_date',
        'attendance_entry_status',
        'attendance_exit_status',
        'entry_position',
        'entry_distance',
        'exit_position',
        'exit_distance',
    ];
    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at'
    ];
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'nip', 'nip');
    }
    public function office()
    {
        return $this->belongsTo(Office::class, 'office_id', 'id');
    }
}