<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryBlog extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public function blogs()
    {
        return $this->hasMany(Blog::class, 'category_id');
    }
}
