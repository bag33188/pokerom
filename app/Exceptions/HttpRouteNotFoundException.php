<?php

namespace App\Exceptions;

use App\Actions\ApiUtilsTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class HttpRouteNotFoundException extends Exception
{
    use ApiUtilsTrait;

    public function render(): bool|JsonResponse
    {
        if ($this->requestExpectsJson()) {
            return Response::json(
                ['message' => 'Error: Not Found.', 'success' => false],
                404 // should be 404
            );
        }
        return false;
    }
}
