<?php

namespace App\Facades;

use App\Queries\RomQueries;
use Illuminate\Support\Facades\Facade;

class RomQueriesFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return RomQueries::class;
    }
}
