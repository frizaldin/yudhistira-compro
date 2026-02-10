<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventTeacherHub extends Model
{
    protected $fillable = [
        'photo',
        'title',
        'judul',
        'date',
        'start_time',
        'end_time',
        'point',
        'link_meeting',
        'created_by'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
