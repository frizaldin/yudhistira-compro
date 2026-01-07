<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submenu extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }
}
