<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThirdBenefit extends Model
{
    protected $fillable = [
        'relation_id',
        'title',
        'description',
        'judul',
        'deskripsi',
        'file',
        'order',
        'visible'
    ];

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class, 'relation_id');
    }
}
