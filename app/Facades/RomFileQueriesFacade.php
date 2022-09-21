<?php

namespace App\Facades;

use App\Queries\RomFileQueries;
use Illuminate\Support\Facades\Facade;

class RomFileQueriesFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return RomFileQueries::class;
    }
}
