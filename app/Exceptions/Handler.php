<?php

namespace App\Exceptions;

use App\Actions\ApiMethodsTrait as ApiMethods;
use App\Actions\ExceptionHandlerMethodsTrait as ExceptionHandlerMethods;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Response;
use MongoDB\Driver\Exception\BulkWriteException;
use Psr\Log\LogLevel;
use Symfony\Component\HttpFoundation\Response as HttpStatus;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiMethods, ExceptionHandlerMethods;

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
            [
                'message' => $e->getMessage(),
                'code' => HttpStatus::HTTP_CONFLICT,
                'headers' => [
                    'X-Attempted-URL' => $this->getCurrentErrorUrl()
                ]
            ]
        ));

        $this->renderable(fn(QueryException $e) => throw App::make(SqlQueryException::class,
            [
                'message' => $e->getMessage(),
                'code' => HttpStatus::HTTP_CONFLICT,
                'headers' => [
                    'X-Attempted-URL' => $this->getCurrentErrorUrl()
                ]
            ]
        ));

        if ($this->isApiRequest() and !$this->isLivewireRequest()) {

            $this->renderable(fn(AuthenticationException $e) => throw App::make(ApiAuthException::class, [
                    'message' => $e->getMessage(),
                    'code' => HttpStatus::HTTP_UNAUTHORIZED,
                    'headers' => [
                        'X-Attempted-URL' => $this->getCurrentErrorUrl()
                    ]
                ]
            ));

            $this->renderable(fn(NotFoundHttpException $e) => throw App::make(NotFoundException::class,
                [
                    'message' => $e->getMessage(),
                    'code' => $this->determineErrorCodeFromException($e),
                    'headers' => [
                        ...$e->getHeaders(),
                        'X-Attempted-URL' => $this->getCurrentErrorUrl()
                    ]
                ]
            ));
        }

        // handle generic \Symfony\Component\HttpKernel\Exception\HttpException
        $this->renderable(function (HttpException $e): JsonResponse|false {
            $httpErrorCode = $this->determineErrorCodeFromException($e);
            if ($this->isApiRequest() or $this->requestExpectsJson()) {
                return Response::json(
                    ['message' => $e->getMessage(), 'success' => false],
                    $httpErrorCode,
                    [
                        ...$e->getHeaders(),
                        'X-Attempted-URL' => $this->getCurrentErrorUrl()
                    ]
                );
            }
            // don't use custom rendering if request is not an API request
            return false;
        });
    }

}
