<?php

namespace Utils\Classes;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Throwable;

# use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;

abstract class AbstractApplicationException extends Exception
{
    public function __construct(private readonly Request $request, string $message, int $code, ?Throwable $previous = null)
    {
        /*! request helper function is also available for use if dependency injection is not desirable */
        parent::__construct($message, $code, $previous);
    }

    /**
     * Determine if request is an API request (ie. made from an API endpoint).
     * @return bool
     */
    protected final function isApiRequest(): bool
    {
        return $this->request->is("api/*");
    }

    /**
     * Determine if request was made by Livewire AJAX call.
     * @return bool
     */
    protected final function isLivewireRequest(): bool
    {
        $livewireHttpHeader = $this->request->header('X-Livewire');
        return isset($livewireHttpHeader);
    }

    abstract public function render(Request $request): false|JsonResponse|RedirectResponse;

    abstract public function report(): bool|null;
}
