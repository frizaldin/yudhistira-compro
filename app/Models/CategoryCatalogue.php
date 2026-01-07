<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryCatalogue extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public function catalogues()
    {
        return $this->hasMany(Catalogue::class, 'category_id');
    }
}
