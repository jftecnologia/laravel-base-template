<?php

declare(strict_types = 1);
use App\Extensions\LaravelContext\Providers\AppContextProvider;
use App\Extensions\LaravelContext\Providers\CorrelationContextProvider;
use App\Extensions\LaravelContext\Providers\RequestContextProvider;

return [
    /*
    |--------------------------------------------------------------------------
    | Enabled
    |--------------------------------------------------------------------------
    | Disables or enables the Laravel App Context package
    */
    'enabled' => env('LARAVEL_CONTEXT_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Providers
    |--------------------------------------------------------------------------
    | Providers that collect context information
    | Run in the listed order
    */
    'providers' => [
        JuniorFontenele\LaravelContext\Providers\TimestampProvider::class,
        AppContextProvider::class,
        JuniorFontenele\LaravelContext\Providers\HostProvider::class,
        CorrelationContextProvider::class,
        RequestContextProvider::class,
        JuniorFontenele\LaravelContext\Providers\UserProvider::class,

        // Add your custom providers here
    ],

    /*
    |--------------------------------------------------------------------------
    | Channels
    |--------------------------------------------------------------------------
    | Where the context will be registered
    */
    'channels' => [
        JuniorFontenele\LaravelContext\Channels\LogChannel::class,

        // Add your custom channels here
    ],
];
