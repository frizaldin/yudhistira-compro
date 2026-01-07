<?php

namespace App\View\Components\Backend\Nav\Sidebar;

use App\Models\Authority;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class Div extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $user = Auth::user();
        $role = $user->authority_id;


        return view('components.backend.nav.sidebar.div', [
            'authority' => Authority::find($user->authorities_id),
            'user' => $user,
        ]);
    }
}
