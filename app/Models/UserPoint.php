<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPoint extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'point',
        'note',
        'source',
        'origin',
    ];

    protected $casts = [
        'origin' => 'array',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'user_id');
    }
}
