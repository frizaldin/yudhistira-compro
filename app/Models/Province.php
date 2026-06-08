<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $table = 'provinces';

    protected $primaryKey = 'code';

    public $incrementing = false;

    protected $keyType = 'int';

    protected $fillable = ['code', 'name'];

    public function cities()
    {
        return $this->hasMany(City::class, 'province_code', 'code');
    }
}
