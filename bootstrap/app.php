<?php

declare(strict_types = 1);

use App\Extensions\LaravelContext\Middlewares\UpdateContext;
use App\Extensions\System\Middlewares\AddSecurityHeaders;
use App\Extensions\System\Middlewares\SetUserLocale;
use App\Extensions\System\Middlewares\TerminatingMiddleware;
use App\Http\Middleware\HandleAppearance;
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use JuniorFontenele\LaravelExceptions\Facades\LaravelException;
use JuniorFontenele\LaravelTracing\Middleware\IncomingTracingMiddleware;
use JuniorFontenele\LaravelTracing\Middleware\OutgoingTracingMiddleware;
use Sentry\Laravel\Integration;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->encryptCookies(except: ['appearance', 'sidebar_state']);

        $middleware->web(append: [
            SetUserLocale::class,
            IncomingTracingMiddleware::class,
            OutgoingTracingMiddleware::class,
            AddSecurityHeaders::class,
            HandleAppearance::class,
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
            UpdateContext::class,
        ]);

        $middleware->append([
            TerminatingMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        Integration::handles($exceptions);

        LaravelException::handles($exceptions);
    })->create();
