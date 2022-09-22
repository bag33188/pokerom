<?php

namespace App\View\Components\Game;

use App\Models\Game;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Delete extends Component
{
    public Game $game;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Game $game)
    {
        $this->game = $game;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|Closure|string
     */
    public function render(): View|Closure|string
    {
        return view('components.game.delete');
    }
}
