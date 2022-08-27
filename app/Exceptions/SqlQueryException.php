<?php

namespace App\Exceptions;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Utils\Classes\AbstractApplicationException as ApplicationException;

/** Handled when there is an error querying `MariaDB` */
class SqlQueryException extends ApplicationException
{
    public function render(Request $request): false|JsonResponse|RedirectResponse
    {
        if (!$this->isApiRequest() AND !$this->isLivewireRequest()) {
            return redirect()->to(url()->previous())->dangerBanner($this->getMessage());
        }
        if ($this->isApiRequest()) {
            return response()->json([
                'message' => $this->getMessage(), 'success' => false,
            ], $this->getCode());
        }
        return false;
    }

    public function report(): ?bool
    {
        return false;
    }
}
