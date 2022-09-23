<?php

namespace App\Observers;

use App\Models\Game;

class GameObserver
{
    /**
     * use database triggers instead of ORM logic
     * @var bool
     */
    private bool $useDbTriggerLogic = true;

    /**
     * Handle events after all transactions are committed.
     *
     * @var bool
     */
    public bool $afterCommit = false;

    public function creating(Game $game): void
    {
        $game->slug = Game::slugifyGameName($game->game_name);
    }

    public function created(Game $game): void
    {
        if ($this->useDbTriggerLogic === false) {
            $rom = $game->rom()->first();
            $rom->has_game = TRUE;
            $rom->game_id = $game->id;
            $rom->saveQuietly();
        }
    }

    public function updating(Game $game): void
    {
        $game->slug = Game::slugifyGameName($game->game_name);
    }

    public function deleted(Game $game): void
    {
        if ($this->useDbTriggerLogic === false) {
            $rom = $game->rom()->first();
            $rom->game_id = NULL;
            $rom->has_game = FALSE;
            $rom->saveQuietly();
        }
    }
}
