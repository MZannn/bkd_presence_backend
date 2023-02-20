<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PresenceDetails extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'presence_id',
        'entry_position',
        'entry_distance',
        'exit_position',
        'exit_distance',
    ];

    public function transaction()
    {
        return $this->belongsTo(Presence::class, 'presence_id', 'id');
    }
}
