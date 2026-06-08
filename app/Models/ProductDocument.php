<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductDocument extends Model
{
    protected $table    = 'product_documents';
    protected $guarded  = [];

    protected $casts = [
        'json_product' => 'array',
    ];
}
