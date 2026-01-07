<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'photo',
        'address',
        'other_photo',
        'url',
        'more_image_1',
        'more_image_2',
        'more_image_3',
        'user_id',
        'visible',
        'city',
        'province',
        'volume',
        'svsc',
        'svlk',
        'jass'
    ];

    public function category()
    {
        return $this->belongsTo(CategoryMaterial::class, 'category_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(UserPemasok::class, 'user_id', 'id');
    }
}
