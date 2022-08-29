<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ListItem extends Component
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
        return /** @lang InjectablePHP */ <<<'blade'
            @php
                $listItemClasses = ['px-6', 'py-2', 'border-b', 'border-gray-200', 'w-full'];
            @endphp
            <li {{ $attributes->merge(['class' => joinCssClasses($listItemClasses)]) }}>
                {{ $slot }}
            </li>
        blade;
    }
}
