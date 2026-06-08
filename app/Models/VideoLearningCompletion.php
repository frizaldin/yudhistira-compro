<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoLearningCompletion extends Model
{
    protected $table = 'video_learning_completions';

    protected $fillable = [
        'user_id',
        'video_learning_id',
        'point_awarded',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'point_awarded' => 'integer',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'user_id');
    }

    public function videoLearning()
    {
        return $this->belongsTo(VideoLearning::class, 'video_learning_id');
    }
}
