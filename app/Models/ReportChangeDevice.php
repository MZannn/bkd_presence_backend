<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportChangeDevice extends Model
{
    use HasFactory;

    protected $table = 'report_change_device';
    protected $fillable = [
        'employee_id',
        'office_id',
        'status',
        'reason'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'nip');
    }

    public function office()
    {
        return $this->belongsTo(Office::class, 'office_id', 'id');
    }
}
