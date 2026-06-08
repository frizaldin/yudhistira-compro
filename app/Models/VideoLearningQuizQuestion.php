<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoLearningQuizQuestion extends Model
{
    protected $table = 'video_learning_quiz_questions';

    protected $fillable = [
        'video_learning_id',
        'video_learning_video_id',
        'question_text',
        'sort_order',
    ];

    public function videoLearning()
    {
        return $this->belongsTo(VideoLearning::class, 'video_learning_id');
    }

    public function video()
    {
        return $this->belongsTo(VideoLearningVideo::class, 'video_learning_video_id');
    }

    public function options()
    {
        return $this->hasMany(VideoLearningQuizOption::class, 'video_learning_quiz_question_id')->orderBy('sort_order');
    }

    /** Quiz untuk seluruh learning (setelah semua video) jika video_learning_video_id null */
    public function isLearningQuiz(): bool
    {
        return $this->video_learning_video_id === null;
    }
}
