<?php

namespace App\View\Components\Frontend;

use App\Models\Configuration;
use App\Models\DigitalProduct;
use App\Models\Service;
use App\Models\SocialMedia;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Layout extends Component
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
        return view('components.frontend.layout', [
            'service' => Service::whereVisible('yes')->get(),
            'digital_product' => DigitalProduct::get(),
            'config' => Configuration::first(),
            'social_media' => SocialMedia::get(),
        ]);
    }
}
