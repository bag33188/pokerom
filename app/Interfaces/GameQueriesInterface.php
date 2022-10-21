<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface GameQueriesInterface
{
    function formatGameTypeSQL(string $gameType): string;

    function getAllRomsWithNoGameSQL(): Collection;

    function getAllGamesThatAreROMHacksSQL(): Collection;
}
