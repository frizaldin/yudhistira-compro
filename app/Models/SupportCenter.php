<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportCenter extends Model
{
    protected $table = 'support_centers';

    protected $fillable = [
        'user_id',
        'ticket_number',
        'status',
        'title',
        'topic',
        'message',
        'datetime',
    ];

    protected $casts = [
        'datetime' => 'datetime',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'user_id');
    }

    public function attachments()
    {
        return $this->hasMany(SupportCenterAttachment::class);
    }

    public function chats()
    {
        return $this->hasMany(SupportCenterChat::class);
    }

    /**
     * Generate ticket number format: TKTYDHSTR0001, TKTYDHSTR0002, ...
     */
    public static function generateTicketNumber(): string
    {
        $prefix = 'TKTYDHSTR';
        $last = static::orderBy('id', 'desc')->first();
        $next = $last ? ((int) preg_replace('/^' . preg_quote($prefix, '/') . '/', '', $last->ticket_number) + 1) : 1;
        return $prefix . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }
}
