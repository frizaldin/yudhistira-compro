<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportCenterChat extends Model
{
    protected $table = 'support_center_chats';

    protected $fillable = [
        'support_center_id',
        'user_id',
        'viewpoint',
        'message',
        'is_read',
        'datetime',
    ];

    protected $casts = [
        'datetime' => 'datetime',
    ];

    public function supportCenter()
    {
        return $this->belongsTo(SupportCenter::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'user_id');
    }

    public function attachments()
    {
        return $this->hasMany(SupportCenterChatAttachment::class);
    }
}
