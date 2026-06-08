<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventTeacherHub extends Model
{
    protected $fillable = [
        'category_id',
        'photo',
        'title',
        'judul',
        'date',
        'start_time',
        'end_time',
        'point',
        'link_meeting',
        'completion_type',
        'completion_token',
        'created_by'
    ];

    public function category()
    {
        return $this->belongsTo(CategoryEventTeacherHub::class, 'category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function questions()
    {
        return $this->hasMany(EventTeacherHubQuestion::class, 'event_id');
    }
}
