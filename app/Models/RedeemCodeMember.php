<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RedeemCodeMember extends Model
{
    protected $table = 'redeem_code_members';

    protected $fillable = [
        'code',
        'serial_code_ebook',
        'book_id',
        'book_json',
        'used',
    ];

    protected $casts = [
        'serial_code_ebook' => 'array',
        'book_json'         => 'array',
    ];
}
