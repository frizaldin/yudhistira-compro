<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OpenTicketChat extends Model
{
    protected $table = 'open_ticket_chats';

    protected $fillable = [
        'open_ticket_id',
        'user_id',
        'viewpoint',
        'message',
        'is_read',
        'datetime',
    ];

    protected $casts = [
        'datetime' => 'datetime',
    ];

    public function openTicket()
    {
        return $this->belongsTo(OpenTicket::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'user_id');
    }

    public function attachments()
    {
        return $this->hasMany(OpenTicketChatAttachment::class);
    }
}
