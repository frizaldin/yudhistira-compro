<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherReward extends Model
{
    protected $fillable = [
        'photo',
        'title',
        'judul',
        'point'
    ];
}
