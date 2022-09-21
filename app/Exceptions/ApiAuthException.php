<?php

namespace App\Exceptions;

use App\Actions\ApiUtilsTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as HttpStatus;
use Utils\Classes\AbstractApplicationException;

class ApiAuthException extends AbstractApplicationException
{
    use ApiUtilsTrait;

    public function render(Request $request): false|JsonResponse|RedirectResponse
    {
        if ($this->requestExpectsJson() || $this->isApiRequest()) {
            return Response::json(
                ['message' => 'Error: Unauthenticated.', 'success' => false], # $e->getTraceAsString();
                HttpStatus::HTTP_UNAUTHORIZED
            );
        }
        // don't use custom rendering if request is not an API request
        return false;
    }

    public function report(): bool|null
    {
        return false;
    }
}
