<?php

namespace App\Exceptions;

use App\Actions\ApiMethodsTrait as ApiMethods;
use App\Actions\ExceptionMethodsTrait as ExceptionHandlerMethods;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as HttpStatus;
use Utils\Classes\AbstractApplicationException as ApplicationException;

class EntityNotFoundException extends ApplicationException
{
    use ApiMethods {
        requestExpectsJson as private;
    }
    use ExceptionHandlerMethods {
        getCurrentErrorUrl as private;
    }

    public function render(Request $request): false|JsonResponse|RedirectResponse
    {
        if ($this->requestExpectsJson()) {
            // make sure it's not `model not found` exception

            $errorIsRouteNotFound = $this->code === HttpStatus::HTTP_NOT_FOUND && strlen($this->message) === 0;

            if ($errorIsRouteNotFound) {
                return Response::json(
                    ['message' => "Route not found: {$this->getCurrentErrorUrl()}", 'success' => false],
                    $this->code,
                    $this->headers
                );
            } else {
                return Response::json(
                    ['message' => $this->message, 'success' => false],
                    $this->code,
                    $this->headers
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
