<?php

namespace App\Exceptions;

use App\Actions\ApiUtilsTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as HttpStatus;
use Utils\Classes\AbstractApplicationException as ApplicationException;

class ApiAuthException extends ApplicationException
{
    use ApiUtilsTrait {
        requestExpectsJson as private;
    }

    public function render(Request $request): false|JsonResponse|RedirectResponse
    {
        if ($this->requestExpectsJson()) {

            return Response::json(
                ['message' => 'Error: Unauthenticated.', 'success' => false],
                HttpStatus::HTTP_UNAUTHORIZED,
                [
                    'X-Attempted-URL' => self::getCurrentErrorUrl(),
                    'X-Stack-Trace' => self::formatErrorTraceString($this->getTraceAsString())
                ]
            );
        }

        return false;
    }

    public function report(): bool|null
    {
        return false;
    }
}
