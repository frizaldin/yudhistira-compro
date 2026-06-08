<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventCertificate extends Model
{
    protected $fillable = [
        'event_id',
        'user_id',
    ];

    public function event()
    {
        return $this->belongsTo(EventTeacherHub::class, 'event_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'user_id');
    }
}
