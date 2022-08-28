<?php

namespace App\Providers;

use App\Interfaces\GameRepositoryInterface;
use App\Interfaces\RomRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Repositories\GameRepository;
use App\Repositories\RomRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(RomRepositoryInterface::class, RomRepository::class);
        $this->app->bind(GameRepositoryInterface::class, GameRepository::class);
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
