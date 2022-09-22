<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as HttpStatus;

class Administrator
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response|RedirectResponse) $next
     * @return Response|RedirectResponse|JsonResponse
     * @throws HttpResponseException
     */
    public function handle(Request $request, Closure $next): Response|RedirectResponse|JsonResponse
    {
        if ($request->user()->isAdmin()) {
            return $next($request);
        } else if (!$request->expectsJson()) {
            return response()->redirectTo(RouteServiceProvider::HOME);
        } else {
            // throws: HttpResponseException
            abort(HttpStatus::HTTP_FORBIDDEN, "This action is forbidden.");
        }
    }
}
