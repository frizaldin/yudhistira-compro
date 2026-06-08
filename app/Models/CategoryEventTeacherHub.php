<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryEventTeacherHub extends Model
{
    protected $fillable = [
        'title',
        'judul',
        'url',
        'order',
        'visible',
    ];

    public function eventTeacherHubs()
    {
        return $this->hasMany(EventTeacherHub::class, 'category_id');
    }
}
