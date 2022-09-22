<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FormSelect extends Component
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
    public function render(): View|Closure|string
    {
        return /** @lang Blade */ <<<'blade'
            @php
                $formSelectClasses = [
                    "border-gray-300", "focus:border-indigo-300", "focus:ring",
                    "focus:ring-indigo-200", "focus:ring-opacity-50", "rounded-md",
                    "shadow-sm", "block", "mt-1", "w-full"
                ];
            @endphp
            <select {{ $attributes->merge(['class' => joinCssClasses($formSelectClasses)]) }}>
                {{ $slot }}
            </select>
        blade;
    }
}
