<?php

namespace Utils\Classes;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as HttpStatus;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

# use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;

abstract class AbstractApplicationException extends Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    abstract public function render(Request $request): false|JsonResponse|RedirectResponse;

    abstract public function report(): bool|null;

    public static function getErrorCodeFromException(Exception $e)
    {
        // the `getStatusCode` method only exists on Exceptions that are instances of HttpException
        if ($e instanceof HttpException) {
            // if `getCode` method returns any status (int value) at all, then use that method, else use the `getStatusCode` method's value (int value)
            return $e->getCode() != 0 ? $e->getCode() : $e->getStatusCode();
        } else {
            return $e->getCode() ?? HttpStatus::HTTP_INTERNAL_SERVER_ERROR;
        }
    }
}
