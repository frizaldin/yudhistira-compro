<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherBook extends Model
{
    protected $table = 'teacher_books';

    protected $fillable = [
        'user_id',
        'code',
        'code_ebook',
        'document',
        'book_id',
    ];

    protected $casts = [
        'code_ebook' => 'array',
        'document'   => 'array',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'user_id');
    }
}
