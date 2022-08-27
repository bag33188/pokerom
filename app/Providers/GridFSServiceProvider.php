<?php

namespace App\Providers;

use App\Services\RomFilesConnection;
use App\Services\RomFilesDatabase;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;


class GridFSServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        // https://laravel.com/docs/9.x/container#binding-scoped
        $this->app->scoped(RomFilesConnection::class, function (Application $app) {
            return new RomFilesConnection($app->make(RomFilesDatabase::class));
        });
        // use singleton??
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

    /**
     * Get the services provided by the provider.
     *
     * _Does not bootstrap until service provider is needed._
     *
     * @return string[]
     */
    public function provides(): array
    {
        return [RomFilesConnection::class];
    }
}
