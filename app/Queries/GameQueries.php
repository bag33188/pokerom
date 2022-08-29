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
            "SELECT HIGH_PRIORITY FORMAT_GAME_TYPE(?) AS `gameType`";
        $query = DB::raw($sql);
        return DB::selectOne($query, [$game_type])->gameType;
    }

    public function getAllRomsWithNoGameSQL(): Collection
    {
        $sql = /** @lang MariaDB */
            "CALL spSelectRomsWithNoGame;";
        $query = DB::raw($sql);
        return Rom::fromQuery($query);
    }
}
