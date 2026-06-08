<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportCenterAttachment extends Model
{
    protected $table = 'support_center_attachments';

    protected $fillable = [
        'support_center_id',
        'path',
        'original_name',
    ];

    protected $appends = ['url'];

    public function supportCenter()
    {
        return $this->belongsTo(SupportCenter::class);
    }

    /**
     * URL untuk akses file (path dari Controller::upload = storage/upload/...).
     */
    public function getUrlAttribute(): ?string
    {
        return $this->path ? asset($this->path) : null;
    }
}
