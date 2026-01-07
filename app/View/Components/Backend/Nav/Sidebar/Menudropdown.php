<?php

namespace App\View\Components\Backend\Nav\Sidebar;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Menudropdown extends Component
{
    /**
     * Create a new component instance.
     */
    public $titlegroup;
    public $menu;
    public $authority;
    public $icon;

    public function __construct(string $icon = "", string $titlegroup, array $menu, $authority)
    {
        $this->icon = $icon;
        $this->titlegroup = $titlegroup;
        $this->menu = $menu;
        $this->authority = $authority;
    }


    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.backend.nav.sidebar.menudropdown');
    }
}

