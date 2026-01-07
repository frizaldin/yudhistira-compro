<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
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
        'meta_keyword',
        'meta_description',
        'url',
        'visible',
        'date',
        'created_by'
    ];

    public function category()
    {
        return $this->belongsTo(CategoryEvent::class, 'category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function eventAssets()
    {
        return $this->hasMany(EventAsset::class);
    }
}
