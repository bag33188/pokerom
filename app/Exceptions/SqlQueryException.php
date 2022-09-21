<?php

namespace App\Exceptions;

use App\Actions\ApiUtilsTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Utils\Classes\AbstractApplicationException as ApplicationException;

/** Handled when there is an error querying `MariaDB` */
class SqlQueryException extends ApplicationException
{
    use ApiUtilsTrait {
        isApiRequest as private;
        isLivewireRequest as private;
    }

    public function render(Request $request): false|JsonResponse|RedirectResponse
    {
        if (!$this->isApiRequest() and !$this->isLivewireRequest()) {
            return redirect()->to(url()->previous())->dangerBanner($this->getMessage());
        } else if ($this->isApiRequest()) {
            return response()->json(
                ['message' => $this->getMessage(), 'success' => false],
                $this->getCode(),
                [
                    'X-Attempted-URL' => self::getCurrentErrorUrl(),
                    'X-Stack-Trace' => self::getFormattedErrorTraceString($this->getTraceAsString())
                ]
            );
        } else return false;
    }

    public function report(): ?bool
    {
        return false;
    }
}
