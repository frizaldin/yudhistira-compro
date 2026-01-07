<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryEvent extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public function events()
    {
        return $this->hasMany(Event::class, 'category_id');
    }
}
