<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionAndSick extends Model
{
    use HasFactory;
    protected $table = 'permission_and_sick';
    protected $fillable = [
        'nip',
        'office_id',
        'presence_id',
        'start_date',
        'end_date',
        'file',
        'status',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
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