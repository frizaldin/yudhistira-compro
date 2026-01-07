<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'category_id',
        'title',
        'description',
        'date',
        'photo',
        'url',
        'visible'
    ];

    public function category()
    {
        return $this->belongsTo(CategoryActivity::class, 'category_id');
    }
}
