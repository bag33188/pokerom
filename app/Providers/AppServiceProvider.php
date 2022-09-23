<?php

namespace App\Providers;

use App\Http\Requests\UploadRomFileRequest;
use App\Models\RomFile;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Blade;
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
            if (App::isLocal()) {
                App::register(IdeHelperServiceProvider::class);
            }
        }
        $this->app->bindMethod([UploadRomFileRequest::class, 'rules'], fn($request, $app) => $request->rules($app->make(RomFile::class)));
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

        // use like so
        // @mdyDate($game->dateReleased)
        Blade::directive('mdyDate', function (string|\Date|\DateTime $expression): string {
            $fmt = 'm/d/Y'; // 01/01/2021 (month/day/year)
            return "<?php echo ($expression)->format($fmt); ?>";
        });
    }
}
