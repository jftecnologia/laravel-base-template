<?php

declare(strict_types = 1);

namespace App\Extensions\LaravelContext\Providers;

use JuniorFontenele\LaravelContext\Providers\AppProvider;

class AppContextProvider extends AppProvider
{
    public function getContext(): array
    {
        return [
            'app' => [
                'name' => config('app.name'),
                'role' => config('app.role'),
                'env' => config('app.env'),
                'version' => config('app.version'),
                'commit' => config('app.commit'),
                'build_date' => config('app.build_date'),
                'debug' => config('app.debug'),
                'url' => config('app.url'),
                'timezone' => config('app.timezone'),
                'locale' => config('app.locale'),
                'origin' => app()->runningInConsole() ? 'console' : 'web',
            ],
        ];
    }
}
