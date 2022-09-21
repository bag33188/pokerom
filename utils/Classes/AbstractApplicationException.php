<?php

namespace Utils\Classes;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
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

    public final static function getCurrentErrorUrl(): string
    {
        return (string)str_replace(Config::get('app.url') . '/', '/', URL::current());
    }

    public static final function formatErrorTraceString(string $trace): string
    {
        $trimAndReplaceLinkBreaksInString = fn(string $subject, string $replace): string => trim(preg_replace("/[\r\n]/", $replace, $subject));
        $modifiedStackTraceString = $trimAndReplaceLinkBreaksInString($trace, _SPACE . '|' . _SPACE);
        $modifiedStackTraceLength = strlen($modifiedStackTraceString);
        return App::isLocal() ? sprintf('[%u] : %s', $modifiedStackTraceLength, $modifiedStackTraceString) : 'null';
    }
}
