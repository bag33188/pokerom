<?php

namespace App\Exceptions;

use App\Actions\ApiUtilsTrait;
use Config;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as HttpStatus;
use Throwable;
use URL;
use Utils\Classes\AbstractApplicationException as ApplicationException;

class RouteNotFoundException extends ApplicationException
{
    use ApiUtilsTrait {
        requestExpectsJson as private;
    }

    public function render(Request $request): false|JsonResponse|RedirectResponse
    {
        if ($this->requestExpectsJson()) {
            $currentErrorRoute = str_replace(Config::get('app.url') . '/', '/', URL::current());

            $errorIsHttpNotFound = $this->code === HttpStatus::HTTP_NOT_FOUND && strlen($this->message) === 0;
            if ($errorIsHttpNotFound) {
                return Response::json(
                    ['message' => "Route not found: $currentErrorRoute", 'success' => false], # $e->getTrace();
                    $this->code
                );
            }
        }
        return false;
    }

    public function report(): bool|null
    {
        return false;
    }
}
