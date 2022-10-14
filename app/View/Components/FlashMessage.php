<?php

namespace App\View\Components;

use App\Enums\FlashMessageTypeEnum as FlashMessageType;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Session;

class FlashMessage extends Component
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
        return view('components.flash-message', [
            'sessionHasMessage' => Session::has('message'),
            'messageType' => Session::get('message-type', FlashMessageType::DEFAULT),
            'sessionFlashTimeout' => (int)session('timeout', 5000)
        ]);
    }
}
