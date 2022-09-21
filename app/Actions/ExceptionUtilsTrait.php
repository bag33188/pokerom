<?php

namespace App\Actions;

use Exception;
use Illuminate\Support\Facades\App;
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

    /**
     * For use in http header value.
     * @param Exception $e
     * @return string
     */
    protected function formatErrorTraceString(Exception $e): string
    {
        $replaceLineBreaksInString = fn(string $subject, string $replace): string => preg_replace("/[\r\n]/", $replace, $subject);
        $trimExtraneousCharsFromString = fn(string $string): string => trim($string, "\x20\r\n\t\xA0\x0B\0");

        $modifiedStackTraceString =
            $replaceLineBreaksInString($trimExtraneousCharsFromString($e->getTraceAsString()), _SPACE . '|' . _SPACE);
        $modifiedStackTraceLength = strlen($modifiedStackTraceString);

        return App::isLocal() ? sprintf('[%u] : %s', $modifiedStackTraceLength, $modifiedStackTraceString) : 'null';
    }

    protected function determineErrorCodeFromException(Exception $e)
    {
        // the `getStatusCode` method only exists on Exceptions that are instances of HttpException
        if ($e instanceof HttpException) {
            // if `getCode` method returns any status (int value) at all, then use that method, else use the `getStatusCode` method's value (int value)
            return $e->getCode() != 0 ? $e->getCode() : $e->getStatusCode();
        } else if ($e instanceof PDOException) {
            return (gettype($e->getCode()) == 'string') ? (int)$e->getCode() : ($e->getCode() ?: HttpStatus::HTTP_INTERNAL_SERVER_ERROR);
        } else {
            return $e->getCode() ?: HttpStatus::HTTP_INTERNAL_SERVER_ERROR;
        }
    }
}
