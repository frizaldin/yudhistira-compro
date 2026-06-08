<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RequestBook extends Model
{
    protected $table = 'request_books';

    protected $fillable = [
        'user_id',
        'number',
        'date',
        'status',
        'code_book',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'user_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(RequestBookDetail::class, 'request_book_id');
    }
}
