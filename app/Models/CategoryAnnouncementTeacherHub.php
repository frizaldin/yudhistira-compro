<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryAnnouncementTeacherHub extends Model
{
    protected $guarded = [];

    public function announcementTeacherHubs()
    {
        return $this->hasMany(AnnouncementTeacherHub::class, 'category_id');
    }
}
