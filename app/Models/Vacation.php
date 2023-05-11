<?php

namespace App\Models;

use App\Models\Employee;
use App\Models\Office;
use App\Models\Presence;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vacation extends Model
{
    use HasFactory;
    protected $table = 'vacation';
    protected $fillable = [
        'nip',
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
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'nip', 'nip');
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