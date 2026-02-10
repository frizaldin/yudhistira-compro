<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryTeacherHub extends Model
{
    protected $guarded = [];

    public function blogTeacherHubs()
    {
        return $this->hasMany(BlogTeacherHub::class, 'category_id');
    }
}
