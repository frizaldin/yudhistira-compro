<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OpenTicketChatAttachment extends Model
{
    protected $table = 'open_ticket_chat_attachments';

    protected $fillable = [
        'open_ticket_chat_id',
        'file',
        'datetime',
        'filesize',
    ];

    protected $casts = [
        'datetime' => 'datetime',
    ];

    protected $appends = ['url'];

    public function openTicketChat()
    {
        return $this->belongsTo(OpenTicketChat::class);
    }

    public function getUrlAttribute(): ?string
    {
        return $this->file ? asset($this->file) : null;
    }
}
