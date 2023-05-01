<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vacation extends Model
{
    use HasFactory;
    protected $table = 'vacation';
    protected $fillable = [
        'employee_id',
        'office_id',
        'presence_id',
        'start_date',
        'end_date',
        'reason',
        'file',
        'status'
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}