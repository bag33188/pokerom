<?php

namespace App\Exceptions;

use App\Actions\ApiMethodsTrait as ApiMethods;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as HttpStatus;
use Utils\Classes\AbstractApplicationException as ApplicationException;

class ApiAuthException extends ApplicationException
{
    use ApiMethods {
        requestExpectsJson as private;
    }

    public function render(Request $request): false|JsonResponse|RedirectResponse
    {
        if ($this->requestExpectsJson()) {
            return Response::json(
                ['message' => 'Error: Unauthenticated.', 'success' => false],
                HttpStatus::HTTP_UNAUTHORIZED,
                $this->headers
            );
        }

        return false;
    }

    public function report(): bool|null
    {
        return false;
    }
}
