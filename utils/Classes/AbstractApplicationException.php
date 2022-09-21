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

    public static final function getFormattedErrorTraceString(string $trace): string
    {
        $stackTraceAsCleanString = trim(preg_replace("/[\r\n]/", _SPACE . '|' . _SPACE, $trace));
        $stackTraceLength = strlen($stackTraceAsCleanString);
        return App::isLocal() ? sprintf('[%u] : %s', $stackTraceLength, $stackTraceAsCleanString) : 'null';
    }
}
