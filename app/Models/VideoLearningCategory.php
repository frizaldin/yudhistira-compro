<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoLearningCategory extends Model
{
    protected $table = 'video_learning_categories';

    protected $fillable = ['title'];

    public function videoLearnings()
    {
        return $this->hasMany(VideoLearning::class, 'category_id');
    }
}
