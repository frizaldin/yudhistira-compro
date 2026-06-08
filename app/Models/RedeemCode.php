<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RedeemCode extends Model
{
    protected $table = 'redeem_codes';

    protected $fillable = [
        'book_title',
        'serial_code',
    ];
}
