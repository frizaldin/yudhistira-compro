<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreativeTeacher extends Model
{
    protected $table = 'creative_teachers';

    protected $fillable = [
        'user_id',
        'number',
        'title',
        'topic',
        'message',
        'link_upload',
        'status',
        'datetime',
    ];

    protected $casts = [
        'datetime' => 'datetime',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'user_id');
    }

    /**
     * Generate number format: CTYD001, CTYD002, ...
     */
    public static function generateNumber(): string
    {
        $prefix = 'CTYD';
        $last = static::orderBy('id', 'desc')->first();
        $next = $last ? ((int) preg_replace('/^' . preg_quote($prefix, '/') . '/', '', $last->number) + 1) : 1;
        return $prefix . str_pad((string) $next, 3, '0', STR_PAD_LEFT);
    }
}
