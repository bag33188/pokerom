<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormSelectLabel extends Component
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
            @props(['selectFieldId', 'text'])
            <label class="block font-medium text-sm text-gray-700" for="{{ $selectFieldId }}">{{ $text }}</label>
        blade;
    }
}
