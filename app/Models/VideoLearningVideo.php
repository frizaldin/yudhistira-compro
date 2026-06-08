<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoLearningVideo extends Model
{
    protected $table = 'video_learning_videos';

    protected $fillable = [
        'video_learning_id',
        'title',
        'video_url',
        'sort_order',
    ];

    public function videoLearning()
    {
        return $this->belongsTo(VideoLearning::class, 'video_learning_id');
    }

    public function quizQuestions()
    {
        return $this->hasMany(VideoLearningQuizQuestion::class, 'video_learning_video_id')->orderBy('sort_order');
    }
}
