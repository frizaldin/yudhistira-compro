<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TutorialVideo extends Model
{
    protected $table = 'tutorial_videos';

    protected $fillable = [
        'title',
        'judul',
        'file',
        'thumbnail',
        'sort_order',
    ];
}
