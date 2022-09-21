<?php

namespace App\View\Components;

use App\Enums\AnchorTypesEnum;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AnchorButton extends Component
{
    public AnchorTypesEnum $btnType;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(AnchorTypesEnum $btnType = AnchorTypesEnum::PRIMARY)
    {
        $this->btnType = $btnType;
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
