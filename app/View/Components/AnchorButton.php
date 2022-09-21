<?php

namespace App\View\Components;

use App\Enums\AnchorTypeEnum as AnchorBtnType;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AnchorButton extends Component
{
    public AnchorBtnType $btnType;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(AnchorBtnType $btnType = AnchorBtnType::PRIMARY)
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
