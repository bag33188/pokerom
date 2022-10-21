<?php

namespace App\View\Components\RomFile;

use App\Models\RomFile;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Download extends Component
{
    public RomFile $romFile;

    /**
     * Create a new component instance.
     *
     * @param RomFile $romFile
     * @return void
     */
    public function __construct(RomFile $romFile)
    {
        $this->romFile = $romFile;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|Closure|string
     */
    public function render(): View|string|Closure
    {
        return /** @lang Blade */ <<<'blade'
            <form {{ $attributes->merge(['class' => 'inline-block']) }}
                  method="GET"
                  action="{{ route('rom-files.download', ['romFile' => $romFile]) }}"
                  name="download-romFile-{{ $romFile->_id }}-form"
                  enctype="multipart/form-data">
                @method('GET')
                @csrf

                <x-jet-button type="submit" id="download-romFile-{{ $romFile->_id }}-btn"
                              @class(['flex', 'flex-row', 'justify-between', 'space-x-2', 'lg:space-x-3'])>
                    {{-- `lg` is @media (min-width: 1024px) --}}
                    <span class="order-0">Download</span>
                    <span class="order-1">@include('partials._download-icon')</span>
                </x-jet-button>
            </form>
        blade;
    }
}
