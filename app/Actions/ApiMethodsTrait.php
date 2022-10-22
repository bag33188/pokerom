<?php

namespace App\Actions;

use Illuminate\Support\Facades\Request;

trait ApiMethodsTrait
{
    protected function isApiRequest(): bool
    {
        return Request::is('api/*');
    }

    protected function isLivewireRequest(): bool
    {
        $livewireHttpHeader = Request::header('X-Livewire');
        return !is_null($livewireHttpHeader);
    }

    protected function requestExpectsJson(): bool
    {
        return Request::expectsJson();
    }
}
