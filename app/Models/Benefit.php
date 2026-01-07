<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Benefit extends Model
{
    protected $fillable = [
        'service_id',
        'title',
        'description',
        'judul',
        'deskripsi',
        'file',
        'order',
        'visible'
    ];

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
