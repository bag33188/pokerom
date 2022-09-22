<?php

namespace App\View\Components\Rom;

use App\Models\Rom;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Delete extends Component
{
    public Rom $rom;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Rom $rom)
    {
        $this->rom = $rom;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|Closure|string
     */
    public function render(): View|Closure|string
    {
        return view('components.rom.delete');
    }
}
