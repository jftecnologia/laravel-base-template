<?php

declare(strict_types = 1);

namespace App\Extensions\LaravelContext\Providers;

use JuniorFontenele\LaravelContext\Providers\RequestProvider;
use JuniorFontenele\LaravelTracing\Facades\LaravelTracing;

class RequestContextProvider extends RequestProvider
{
    public function getContext(): array
    {
        return [
            'request' => [
                'id' => LaravelTracing::get('request_id'),
                'ip' => request()->ip(),
                'method' => request()->method(),
                'url' => request()->fullUrl(),
                'host' => request()->getHost(),
                'scheme' => request()->getScheme(),
                'locale' => request()->getLocale(),
                'referer' => request()->header('referer'),
                'user_agent' => request()->userAgent(),
                'accept_language' => request()->header('accept-language'),
            ],
        ];
    }
}
