<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OpenTicketAttachment extends Model
{
    protected $table = 'open_ticket_attachments';

    protected $fillable = [
        'open_ticket_id',
        'path',
        'original_name',
    ];

    protected $appends = ['url'];

    public function openTicket()
    {
        return $this->belongsTo(OpenTicket::class);
    }

    /**
     * URL untuk akses file (path dari Controller::upload = storage/upload/...).
     */
    public function getUrlAttribute(): ?string
    {
        return $this->path ? asset($this->path) : null;
    }
}
