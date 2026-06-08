<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportCenterChatAttachment extends Model
{
    protected $table = 'support_center_chat_attachments';

    protected $fillable = [
        'support_center_chat_id',
        'file',
        'datetime',
        'filesize',
    ];

    protected $casts = [
        'datetime' => 'datetime',
    ];

    protected $appends = ['url'];

    public function supportCenterChat()
    {
        return $this->belongsTo(SupportCenterChat::class);
    }

    public function getUrlAttribute(): ?string
    {
        return $this->file ? asset($this->file) : null;
    }
}
