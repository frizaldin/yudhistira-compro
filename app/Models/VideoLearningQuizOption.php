<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoLearningQuizOption extends Model
{
    protected $table = 'video_learning_quiz_options';

    protected $fillable = [
        'video_learning_quiz_question_id',
        'option_text',
        'is_correct',
        'sort_order',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    public function question()
    {
        return $this->belongsTo(VideoLearningQuizQuestion::class, 'video_learning_quiz_question_id');
    }
}
