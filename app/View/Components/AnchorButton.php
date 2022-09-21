<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AnchorButton extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(public string $type = 'primary')
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|Closure|string
     */
    public function render(): View|string|Closure
    {
        return view('components.anchor-button');
    }
}
