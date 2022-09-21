<?php

namespace App\View\Components;

use Closure;
use Faker\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PunchButton extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
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
        $randomDomainWord = Factory::create('en_US')->domainWord;
        return view('components.punch-button', [
            'fallbackBtnName' => "$randomDomainWord-punch-btn"
        ]);
    }
}
