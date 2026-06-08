<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoLearningQuizAnswer extends Model
{
    protected $table = 'video_learning_quiz_answers';

    protected $fillable = [
        'user_id',
        'video_learning_quiz_question_id',
        'video_learning_quiz_option_id',
        'is_correct',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'user_id');
    }

    public function question()
    {
        return $this->belongsTo(VideoLearningQuizQuestion::class, 'video_learning_quiz_question_id');
    }

    public function option()
    {
        return $this->belongsTo(VideoLearningQuizOption::class, 'video_learning_quiz_option_id');
    }
}
