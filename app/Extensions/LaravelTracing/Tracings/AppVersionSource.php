<?php

declare(strict_types = 1);

namespace App\Extensions\LaravelTracing\Tracings;

use JuniorFontenele\LaravelTracing\Tracings\Contracts\TracingSource;

class AppVersionSource implements TracingSource
{
    public function headerName(): string
    {
        return config('laravel-tracing.tracings.app_version.header', 'X-App-Version');
    }

    public function resolve(\Illuminate\Http\Request $request): string
    {
        return config('app.version');
    }

    public function restoreFromJob(string $value): string
    {
        return $value;
    }
}
