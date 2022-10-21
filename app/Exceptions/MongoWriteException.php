<?php

namespace App\Exceptions;

use App\Actions\ApiMethodsTrait as ApiMethods;
use Classes\AbstractApplicationException as ApplicationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/** Handled when there is an error querying `MongoDB` */
class MongoWriteException extends ApplicationException
{
    use ApiMethods {
        isApiRequest as private;
        isLivewireRequest as private;
    }

    public function render(Request $request): false|JsonResponse|RedirectResponse
    {
        if (!$this->isApiRequest() and !$this->isLivewireRequest()) {
            /** @noinspection PhpVoidFunctionResultUsedInspection */
            return redirect()->to(url()->previous())->dangerBanner($this->getMessage());
        } else if ($this->isApiRequest()) {
            $gridfs = config('database.connections.mongodb');
            return response()->json(
                [
                    'success' => false,
                    'database' => $gridfs['database'],
                    'driver' => $gridfs['driver'],
                    'message' => $this->getMessage()
                ],
                $this->getCode(),
                $this->headers
            );
        } else return false;
    }

    public function report(): ?bool
    {
        return false;
    }
}
