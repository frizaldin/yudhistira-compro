<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'cities';

    protected $primaryKey = 'code';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = ['code', 'province_code', 'name'];

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_code', 'code');
    }
}
