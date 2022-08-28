<?php

namespace App\Providers;

use App\Interfaces\GameQueriesInterface;
use App\Interfaces\RomQueriesInterface;
use App\Queries\GameQueries;
use App\Queries\RomQueries;
use Illuminate\Support\ServiceProvider;

class QueryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(RomQueriesInterface::class, RomQueries::class);
        $this->app->bind(GameQueriesInterface::class, GameQueries::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }
}
