<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoLearningVideoProgress extends Model
{
    protected $table = 'video_learning_video_progress';

    protected $fillable = [
        'user_id',
        'video_learning_video_id',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'user_id');
    }

    public function video()
    {
        return $this->belongsTo(VideoLearningVideo::class, 'video_learning_video_id');
    }
}
