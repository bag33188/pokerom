<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    private static bool $useIdeHelper = false;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        if (self::$useIdeHelper === true) {
            if (\Illuminate\Support\Facades\App::isLocal()) {
                \Illuminate\Support\Facades\App::register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
            }
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $view_name = str_replace('.', '-', $view->getName());
            View::share('view_name', $view_name);
        });
    }
}
