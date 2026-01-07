<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SampleProduct extends Model
{
    protected $guarded = [];

    public function products()
    {
        return $this->hasMany(Product::class, 'sample_product_id');
    }
}
