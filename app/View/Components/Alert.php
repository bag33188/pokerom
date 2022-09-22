<?php

namespace App\View\Components;

use App\Enums\AlertTypeEnum as AlertType;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Alert extends Component
{
    public AlertType $alertType;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(AlertType $alertType = AlertType::MESSAGE)
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
