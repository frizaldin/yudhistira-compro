<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoLearning extends Model
{
    protected $table = 'video_learnings';

    protected $fillable = [
        'category_id',
        'title',
        'judul',
        'description',
        'deskripsi',
        'thumbnail',
        'point',
        'sort_order',
    ];

    protected $casts = [
        'point' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(VideoLearningCategory::class, 'category_id');
    }

    public function videos()
    {
        return $this->hasMany(VideoLearningVideo::class, 'video_learning_id')->orderBy('sort_order');
    }

    public function quizQuestions()
    {
        return $this->hasMany(VideoLearningQuizQuestion::class, 'video_learning_id')->orderBy('sort_order');
    }
}
