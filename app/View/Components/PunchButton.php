<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PunchButton extends Component
{
    public string $btnType;

    /**
     * Create a new component instance.
     *
     * @param string $btnType
     */
    public function __construct(string $btnType)
    {
        $this->btnType = strtolower($btnType);
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|Closure|string
     */
    public function render(): View|string|Closure
    {
        return view('components.punch-button', [
            'userIsAdmin' => auth()->user()->isAdmin(),
        ]);
    }
}
