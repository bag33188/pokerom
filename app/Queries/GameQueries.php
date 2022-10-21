<?php

namespace App\Queries;

use App\Interfaces\GameQueriesInterface;
use App\Models\Game;
use App\Models\Rom;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class GameQueries implements GameQueriesInterface
{
    private readonly Game $game;

    public function __construct(Game $game)
    {
        $this->game = $game;
    }

    public function formatGameTypeSQL(string $game_type): string
    {
        $sql = /** @lang MariaDB */
            "SELECT HIGH_PRIORITY FORMAT_GAME_TYPE(?) AS `game_type`";
        $query = DB::raw($sql);
        return DB::scalar($query, [$game_type]);
    }

    public function getAllRomsWithNoGameSQL(): Collection
    {
        $sql = /** @lang MariaDB */
            "CALL spSelectRomsWithNoGame;";
        $query = DB::raw($sql);
        return Rom::fromQuery($query);
    }

    public function getAllGamesThatAreROMHacksSQL(): Collection
    {
        $sql = /** @lang MariaDB */
            "CALL spSelectGamesThatAreROMHacks;";
        $query = DB::raw($sql);
        return $this->game->fromQuery($query);
    }
}
