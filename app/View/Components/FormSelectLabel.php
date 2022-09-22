<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FormSelectLabel extends Component
{
    public string $text;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $text)
    {
        $this->text = $text;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|Closure|string
     */
    public function render(): View|string|Closure
    {
        return /** @lang Blade */ <<<'blade'
            @php
                $formSelectLabelClasses = array(
                    'block',
                    'text-sm',
                    'font-medium',
                    'text-gray-700'
                );
            @endphp
            <label {{ $attributes->merge(['class' => joinCssClasses($formSelectLabelClasses)]) }}>{{ $text }}</label>
        blade;
    }
}
