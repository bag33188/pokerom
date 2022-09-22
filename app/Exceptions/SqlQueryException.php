<?php

namespace App\Exceptions;

use App\Actions\ApiMethodsTrait as ApiMethods;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Utils\Classes\AbstractApplicationException as ApplicationException;

/** Handled when there is an error querying `MariaDB` */
class SqlQueryException extends ApplicationException
{
    use ApiMethods {
        isApiRequest as private;
        isLivewireRequest as private;
    }

    public function render(Request $request): false|JsonResponse|RedirectResponse
    {
        if (!$this->isApiRequest() and !$this->isLivewireRequest()) {
            return redirect()->to(url()->previous())->dangerBanner($this->getMessage());
        } else if ($this->isApiRequest()) {
            $mariadb = config('database.connections.mysql');
            return response()->json(
                [
                    'success' => false,
                    'database' => $mariadb['database'],
                    'driver' => $mariadb['driver'],
                    'message' => $this->getMessage(),
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
