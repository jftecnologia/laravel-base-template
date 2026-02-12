<?php

declare(strict_types = 1);

namespace App\Extensions\LaravelContext\Providers;

use JuniorFontenele\LaravelContext\Providers\AbstractProvider;
use JuniorFontenele\LaravelTracing\Facades\LaravelTracing;

class CorrelationContextProvider extends AbstractProvider
{
    public function isCacheable(): bool
    {
        return false;
    }

    public function shouldRun(): bool
    {
        return LaravelTracing::has('correlation_id');
    }

    public function getContext(): array
    {
        return [
            'correlation_id' => LaravelTracing::get('correlation_id'),
        ];
    }
}
