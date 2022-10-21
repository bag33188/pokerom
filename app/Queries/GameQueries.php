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

    public function formatGameTypeSQL(string $gameType): string
    {
        $sql = /** @lang MariaDB */
            "SELECT HIGH_PRIORITY FORMAT_GAME_TYPE(?) AS `game_type`";
        $query = DB::raw($sql);
        // https://laravel.com/docs/9.x/database#selecting-scalar-values
        return DB::scalar($query, [$gameType]);
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
