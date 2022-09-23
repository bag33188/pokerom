<?php

namespace App\Providers;

use App\Services\RomFileProcessor;
use App\Services\RomFilesConnection;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Utils\Modules\GridFSConnection;


class GridFSServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
//        $this->app->when(RomFileProcessor::class)
//            ->needs(RomFilesConnection::class)
//            ->give(function (Application $app) {
//                return $app->make(RomFilesConnection::class, ['databaseName' => 'pokerom_files', 'bucketName' => 'rom', 'chunkSize' => 0xFF000]);
//            });
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
        return [RomFilesConnection::class, RomFileProcessor::class];
    }
}
