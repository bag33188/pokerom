<?php

namespace App\Facades;

use App\Queries\GameQueries;
use Illuminate\Support\Facades\Facade;

class GameQueriesFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return GameQueries::class;
    }
}
