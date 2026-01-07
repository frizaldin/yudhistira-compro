<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SecondBenefit extends Model
{
    protected $fillable = [
        'digital_product_id',
        'title',
        'description',
        'judul',
        'deskripsi',
        'file',
        'order',
        'visible'
    ];

    public function digitalProduct()
    {
        return $this->belongsTo(DigitalProduct::class, 'digital_product_id');
    }
}
