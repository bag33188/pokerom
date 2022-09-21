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

    public function getAllGamesThatAreROMHacksSQL(): Collection
    {
        $sql = /** @lang MariaDB */
            "SELECT * FROM `games` WHERE `game_type` = :rom_hack OR `generation` = 0 ORDER BY `date_released` DESC LIMIT :limit_results_to;";
        $query = DB::raw($sql);
        return $this->game->fromQuery($query, [
            'rom_hack' => GAME_TYPES[self::findRomHackStringEntityIndexOfGameTypes()],
            'limit_results_to' => 10
        ]);
    }

    private static function findRomHackStringEntityIndexOfGameTypes(): int
    {
        return array_search('hack', GAME_TYPES, false);
    }
}
