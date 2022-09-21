<?php

namespace App\Exceptions;

use App\Actions\ApiUtilsTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as HttpStatus;
use Utils\Classes\AbstractApplicationException as ApplicationException;

class RouteNotFoundException extends ApplicationException
{
    use ApiUtilsTrait {
        requestExpectsJson as private;
    }

    public function render(Request $request): false|JsonResponse|RedirectResponse
    {
        if ($this->requestExpectsJson()) {

            $errorIsHttpNotFound = $this->code === HttpStatus::HTTP_NOT_FOUND && strlen($this->message) === 0;

            if ($errorIsHttpNotFound) {
                return Response::json(
                    ['message' => "Route not found: {self::getCurrentErrorUrl()}", 'success' => false],
                    $this->code,
                    [
                        'X-Attempted-URL' => self::getCurrentErrorUrl(),
                        'X-Stack-Trace' => self::getFormattedErrorTraceString($this->getTraceAsString())
                    ]
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
