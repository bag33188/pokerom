<?php

namespace App\Exceptions;

use App\Actions\ApiUtilsTrait;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Response;
use MongoDB\Driver\Exception\BulkWriteException;
use PDOException;
use Psr\Log\LogLevel;
use Symfony\Component\HttpFoundation\Response as HttpStatus;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use Utils\Classes\AbstractApplicationException as ApplicationException;

class Handler extends ExceptionHandler
{
    use ApiUtilsTrait;

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

        if ($this->isApiRequest() and !$this->isLivewireRequest()) {
            $this->renderable(fn(AuthenticationException $e) => throw App::make(ApiAuthException::class, [
                'message' => $e->getMessage(),
                'code' => HttpStatus::HTTP_UNAUTHORIZED,
            ]));
            $this->renderable(fn(NotFoundHttpException $e) => throw App::make(RouteNotFoundException::class,
                ['message' => $e->getMessage(), 'code' => self::determineErrorCodeFromException($e)]));
        }

        // handle generic \Symfony\Component\HttpKernel\Exception\HttpException
        $this->renderable(function (HttpException $e): JsonResponse|false {
            $httpErrorCode = self::determineErrorCodeFromException($e);
            if ($this->isApiRequest() or $this->requestExpectsJson()) {
                return Response::json(
                    ['message' => $e->getMessage(), 'success' => false],
                    $httpErrorCode,
                    array(
                        ...$e->getHeaders(),
                        'X-Attempted-URL' => ApplicationException::getCurrentErrorUrl(),
                        'X-Stack-Trace' => ApplicationException::getFormattedErrorTraceString($e->getTraceAsString())
                    )
                );
            }
            // don't use custom rendering if request is not an API request
            return false;
        });
    }

    private static function determineErrorCodeFromException(Exception $e)
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
