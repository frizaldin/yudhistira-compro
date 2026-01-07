<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timeline extends Model
{
    protected $fillable = [
        'year',
        'photo',
        'description',
        'deskripsi',
        'visible'
    ];
}

