<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Catalogue extends Model
{
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(CategoryCatalogue::class, 'category_id');
    }
}

