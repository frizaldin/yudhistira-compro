<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryGuideBook extends Model
{
    protected $fillable = [
        'title',
        'judul',
        'url',
        'order',
        'visible',
    ];

    public function guideBooks()
    {
        return $this->hasMany(GuideBook::class, 'category_id');
    }
}
