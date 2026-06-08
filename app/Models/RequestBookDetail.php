<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequestBookDetail extends Model
{
    protected $table = 'request_book_details';

    protected $fillable = [
        'request_book_id',
        'book_id',
        'book'
    ];
    
    protected $casts = [
        'book' => 'json'];

    public function requestBook(): BelongsTo
    {
        return $this->belongsTo(RequestBook::class, 'request_book_id');
    }
}
