<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnnouncementTeacherHub extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'nama',
        'photo',
        'overview',
        'pratinjau',
        'description',
        'deskripsi',
        'tags',
        'url',
        'visible',
        'date',
        'created_by'
    ];

    public function category()
    {
        return $this->belongsTo(CategoryAnnouncementTeacherHub::class, 'category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
