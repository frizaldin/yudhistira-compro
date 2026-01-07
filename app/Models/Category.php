<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public function blogs()
    {
        return $this->hasMany(Blog::class, 'category_id');
    }
    public function events()
    {
        return $this->hasMany(Event::class, 'category_id');
    }
    public function subcategories()
    {
        return $this->hasMany(Subcategory::class, 'category_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
