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

class NotFoundException extends ApplicationException
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
            // route not found always has an error message whose string length is 0
            $errorIsRouteNotFound = $this->code === HttpStatus::HTTP_NOT_FOUND && strlen($this->message) === 0;
            // model not found always has an error message whose string matches 'No query results for model'
            $errorIsModelNotFound = $this->code === HttpStatus::HTTP_NOT_FOUND && str_contains(strtoupper($this->message), strtoupper('No query results for model'));
            if ($errorIsModelNotFound) {
                return Response::json(
                    ['message' => $this->message, 'success' => false],
                    $this->code,
                    $this->headers
                );
            }
            if ($errorIsRouteNotFound) {
                return Response::json(
                    ['message' => "Route not found: {$this->getCurrentErrorUrl()}", 'success' => false],
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
