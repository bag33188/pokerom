<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ListGroup extends Component
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
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return /** @lang InjectablePHP */ <<<'blade'
            @php
                $listGroupClasses = ['bg-white', 'rounded-lg', 'border', 'border-gray-200', 'text-gray-900'];
            @endphp
            <ul {{ $attributes->merge(['class' => joinCssClasses($listGroupClasses)]) }}>
                {{$slot}}
            </ul>
        blade;
    }
}
