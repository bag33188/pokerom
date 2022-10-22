<?php

namespace Classes;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Throwable;

# use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;

abstract class AbstractApplicationException extends Exception
{
    protected array $headers = [];

    public function __construct(string $message = "", int $code = 0, array $headers = [], ?Throwable $previous = null)
    {
        $this->headers = $headers;
        parent::__construct($message, $code, $previous);
    }

    abstract public function render(Request $request): false|JsonResponse|RedirectResponse;

    abstract public function report(): bool|null;
}
