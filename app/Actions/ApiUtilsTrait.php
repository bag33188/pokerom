<?php

namespace App\Actions;

use Illuminate\Support\Facades\Request;

trait ApiUtilsTrait
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
}
