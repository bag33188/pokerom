<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PunchButton extends Component
{
    public string $btnName;

    /**
     * Create a new component instance.
     *
     * @param string $btnName
     * @return void
     */
    public function __construct(string $btnName)
    {
        $this->btnName = $btnName;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|Closure|string
     */
    public function render(): View|string|Closure
    {
        return view('components.punch-button');
    }
}
