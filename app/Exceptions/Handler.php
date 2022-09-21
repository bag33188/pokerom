<?php

namespace App\Exceptions;

use Config;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Response;
use MongoDB\Driver\Exception\BulkWriteException;
use Psr\Log\LogLevel;
use Symfony\Component\HttpFoundation\Response as HttpStatus;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;
use URL;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<Throwable>, LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(fn(BulkWriteException $e) => throw App::make(MongoWriteException::class,
            ['message' => $e->getMessage(), 'code' => HttpStatus::HTTP_CONFLICT]));

        $this->renderable(fn(QueryException $e) => throw App::make(SqlQueryException::class,
            ['message' => $e->getMessage(), 'code' => HttpStatus::HTTP_CONFLICT]));

        /*! make sure correct JSON response is returned during an API request */

        // handle \Illuminate\Auth\AuthenticationException
        $this->renderable(function (AuthenticationException $e, Request $request): JsonResponse|false {
            if ($request->expectsJson()) {
                return Response::json(
                    ['message' => 'Error: Unauthenticated.', 'success' => false], # $e->getTraceAsString();
                    HttpStatus::HTTP_UNAUTHORIZED,
                    [
                        'X-Http-Auth-Exception-Original-Message' => $e->getMessage(),
                    ]
                );
            }
            // don't use custom rendering if request is not an API request
            return false;
        });

        $this->renderable(function (HttpException $e, Request $request): JsonResponse|false {

            // if `getCode` method returns any status (int value) at all, then use that method, else use the `getStatusCode` method's value (int value)
            $statusCode = $e->getCode() != 0 ? $e->getCode() : $e->getStatusCode();

            // set default message value to message of exception being thrown by request/response
            $message = $e->getMessage();

            if ($request->is("api/*")) {
                $currentErrorRoute = str_replace(Config::get('app.url') . '/', '/', URL::current());

                $responseErrorsIsRouteNotFound = fn() => $statusCode === HttpStatus::HTTP_NOT_FOUND && strlen($message) === 0;

                if ($responseErrorsIsRouteNotFound()) $message = "Route not found: $currentErrorRoute";

                return Response::json(
                    ['message' => $message, 'success' => false], # $e->getTrace();
                    $statusCode,
                    [...$e->getHeaders()]
                );
            }
            // don't use custom rendering if request is not an API request
            return false;
        });
    }
}
