<?php

namespace App\Repositories;

use App\Interfaces\GameRepositoryInterface;
use App\Models\Rom;
use DB;
use Illuminate\Database\Eloquent\Collection;

class GameRepository implements GameRepositoryInterface
{
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
