<?php

namespace App\Http\Controllers;

use App\Actions\ApiMethodsTrait as ApiMethods;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, ApiMethods;

    protected final function getCurrentAuthGuard(): string
    {
        return Auth::getDefaultDriver();
    }

    protected final function getCurrentRouteName(): ?string
    {
        return Request::route()->getName();
    }

    protected final function getCurrentRouteGlob(): ?string
    {
        return Route::getFacadeRoot()->current()->uri();
    }
}
