<?php

namespace App\View\Components\RomFile;

use App\Models\RomFile;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Delete extends Component
{
    public RomFile $romFile;

    /**
     * Create a new component instance.
     *
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
    public function render(): View|Closure|string
    {
        return view('components.rom-file.delete');
    }
}
