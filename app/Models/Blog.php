<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'nama',
        'description',
        'deskripsi',
        'overview',
        'pratinjau',
        'photo',
        'tags',
        'meta_description',
        'meta_keyword',
        'url',
        'visible',
        'date',
        'created_by'
    ];

    public function category()
    {
        return $this->belongsTo(CategoryBlog::class, 'category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
