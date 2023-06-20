<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRules extends Model
{
    use HasFactory;
    protected $table = 'leave_rules';
    protected $fillable = [
        'leave_name'
    ];
}
