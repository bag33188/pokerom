<?php

namespace App\View\Components;

use Closure;
use Faker\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PunchButton extends Component
{
    /**
     * @var string
     */
    public $btnName;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($btnName)
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
        $randomWord = Factory::create('en_US')->word;
        return view('components.punch-button', [
            'fallbackBtnName' => "$randomWord-punch-btn",
        ]);
    }
}
