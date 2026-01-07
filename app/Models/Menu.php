<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public function submenus()
    {
        return $this->hasMany(Submenu::class, 'menu_id');
    }
}
