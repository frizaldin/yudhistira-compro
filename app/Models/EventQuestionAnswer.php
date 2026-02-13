<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventQuestionAnswer extends Model
{
    protected $fillable = [
        'event_id',
        'question_id',
        'user_id',
        'answer',
    ];

    public function event()
    {
        return $this->belongsTo(EventTeacherHub::class, 'event_id');
    }

    public function question()
    {
        return $this->belongsTo(EventTeacherHubQuestion::class, 'question_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'user_id');
    }
}
