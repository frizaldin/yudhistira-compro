<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuideBook extends Model
{
    protected $fillable = [
        'category_id',
        'thumbnail',
        'title',
        'judul',
        'file',
    ];

    public function category()
    {
        return $this->belongsTo(CategoryGuideBook::class, 'category_id');
    }
}
