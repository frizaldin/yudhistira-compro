<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DigitalLearningSupport extends Model
{
    protected $fillable = [
        'title',
        'judul',
        'video_tipe',
        'file',
        'embed',
        'created_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
