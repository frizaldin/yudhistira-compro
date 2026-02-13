<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventTeacherHubQuestion extends Model
{
    protected $fillable = [
        'event_id',
        'title',
        'judul',
    ];

    public function event()
    {
        return $this->belongsTo(EventTeacherHub::class, 'event_id');
    }
}
