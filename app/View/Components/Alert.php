<?php

namespace App\View\Components;

use App\Enums\AlertTypesEnum;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Alert extends Component
{
    public AlertTypesEnum $alertType;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(AlertTypesEnum $alertType = AlertTypesEnum::DEFAULT)
    {
        $this->alertType = $alertType;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|Closure|string
     */
    public function render(): View|string|Closure
    {
        return view('components.alert');
    }
}
