<?php

namespace App\Actions;

use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use PDOException;
use Symfony\Component\HttpFoundation\Response as HttpStatus;
use Symfony\Component\HttpKernel\Exception\HttpException;

trait ExceptionUtilsTrait
{
    protected function getCurrentErrorUrl(): string
    {
        return (string)str_replace(Config::get("app.url") . '/', '/', URL::current());
    }

    protected function determineErrorCodeFromException(Exception $e)
    {
        // the `getStatusCode` method only exists on Exceptions that are instances of HttpException
        if ($e instanceof HttpException) {
            // if `getCode` method returns any status (int value) at all, then use that method, else use the `getStatusCode` method's value (int value)
            return $e->getCode() != 0 ? $e->getCode() : $e->getStatusCode();
        } else if ($e instanceof PDOException) { // PDOException returns a string value for the error code
            return (gettype($e->getCode()) == 'string') ? (int)$e->getCode() : ($e->getCode() ?: HttpStatus::HTTP_INTERNAL_SERVER_ERROR);
        } else {
            return $e->getCode() ?: HttpStatus::HTTP_INTERNAL_SERVER_ERROR;
        }
    }
}
