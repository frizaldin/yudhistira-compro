<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherBookmark extends Model
{
    protected $table = 'teacher_bookmarks';

    protected $fillable = [
        'user_id',
        'type',
        'reference_id',
    ];

    public const TYPE_EVENT = 'event_teacher_hub';
    public const TYPE_VIDEO_LEARNING = 'video_learning';

    public static function allowedTypes(): array
    {
        return [self::TYPE_EVENT, self::TYPE_VIDEO_LEARNING];
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'user_id');
    }
}
