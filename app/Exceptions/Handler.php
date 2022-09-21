<?php

namespace App\Exceptions;

use App\Actions\ApiUtilsTrait;
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
use PDOException;
use Utils\Classes\AbstractApplicationException as ApplicationException;

class Handler extends ExceptionHandler
{
    use ApiUtilsTrait {
        isApiRequest as private;
        requestExpectsJson as private;
    }

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

        $this->renderable(fn(AuthenticationException $e) => throw App::make(ApiAuthException::class));

        $this->renderable(fn(NotFoundHttpException $e) => throw App::make(RouteNotFoundException::class,
            ['message' => $e->getMessage(), 'code' => ApplicationException::getErrorCodeFromException($e)]));

        // handle generic \Symfony\Component\HttpKernel\Exception\HttpException
        $this->renderable(function (HttpException $e): JsonResponse|false {

            $statusCode = ApplicationException::getErrorCodeFromException($e);

            // set default message value to message of exception being thrown by request/response
            $message = $e->getMessage();

            if ($this->isApiRequest() || $this->requestExpectsJson()) {
                return Response::json(
                    ['message' => $message, 'success' => false], # $e->getTrace(); $e->getTraceAsString();
                    $statusCode,
                    [...$e->getHeaders()]
                );
            }
            // don't use custom rendering if request is not an API request
            return false;
        });
    }

    private static function getErrorCodeFromException(Exception $e)
    {
        // the `getStatusCode` method only exists on Exceptions that are instances of HttpException
        if ($e instanceof HttpException) {
            // if `getCode` method returns any status (int value) at all, then use that method, else use the `getStatusCode` method's value (int value)
            return $e->getCode() != 0 ? $e->getCode() : $e->getStatusCode();
        } else if ($e instanceof PDOException) {
            return (gettype($e->getCode()) == 'string') ? (int)$e->getCode() : HttpStatus::HTTP_INTERNAL_SERVER_ERROR;
        } else {
            return $e->getCode() ?: HttpStatus::HTTP_INTERNAL_SERVER_ERROR;
        }
    }
}
