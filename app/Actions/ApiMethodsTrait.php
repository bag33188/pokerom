<?php

namespace App\Actions;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Route;

trait ApiMethodsTrait
{
    protected function isApiRequest(): bool
    {
        return Request::is('api/*');
    }

    protected function isLivewireRequest(): bool
    {
        $livewireHttpHeader = Request::header('X-Livewire');
        return isset($livewireHttpHeader);
    }

    protected function requestExpectsJson(): bool
    {
        return Request::expectsJson();
    }

    protected function getCurrentAuthGuard(): string
    {
        return Auth::getDefaultDriver();
    }

    protected function getCurrentRouteName(): ?string
    {
        return Request::route()->getName();
    }

    protected function getCurrentRouteGlob(): ?string
    {
        return Route::getFacadeRoot()->current()->uri();
    }
}
