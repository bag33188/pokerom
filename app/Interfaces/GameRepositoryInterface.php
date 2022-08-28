<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface GameRepositoryInterface
{
    function formatGameTypeSQL(string $game_type): string;

    function getAllRomsWithNoGameSQL(): Collection;
}
