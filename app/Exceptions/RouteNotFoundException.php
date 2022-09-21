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
use Utils\Classes\AbstractApplicationException;

class RouteNotFoundException extends AbstractApplicationException
{
    use ApiUtilsTrait {
        isApiRequest as private;
        requestExpectsJson as private;
    }

    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function render(Request $request): false|JsonResponse|RedirectResponse
    {
        if ($this->isApiRequest() || $this->requestExpectsJson()) {
            $currentErrorRoute = str_replace(Config::get('app.url') . '/', '/', URL::current());

            $responseErrorIsRouteNotFound = $this->code === HttpStatus::HTTP_NOT_FOUND && strlen($this->message) === 0;
            if ($responseErrorIsRouteNotFound) {
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
