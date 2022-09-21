<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View as ViewFactory;

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
            if (App::environment('local')) {
                App::register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
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
        View::composer('*', function (ViewFactory $view) {
            $view_name = str_replace('.', '-', $view->getName());
            View::share('view_name', $view_name);
        });
    }
}
