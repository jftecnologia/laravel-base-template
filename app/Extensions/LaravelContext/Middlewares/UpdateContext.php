<?php

declare(strict_types = 1);

namespace App\Extensions\LaravelContext\Middlewares;

use Closure;
use Illuminate\Http\Request;
use JuniorFontenele\LaravelContext\Facades\LaravelContext;
use Symfony\Component\HttpFoundation\Response;

class UpdateContext
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        LaravelContext::build();

        return $next($request);
    }
}
