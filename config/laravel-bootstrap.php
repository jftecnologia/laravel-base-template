<?php

declare(strict_types = 1);

return [
    'http' => [
        'force_https' => env('LARAVEL_BOOTSTRAP_FORCE_HTTPS', true),
        'force_https_environments' => explode(',', env('LARAVEL_BOOTSTRAP_FORCE_HTTPS_ENVIRONMENTS', 'production,staging')),
    ],

    'models' => [
        'unguarded' => env('LARAVEL_BOOTSTRAP_MODELS_UNGUARDED', true),
        'auto_eager_load' => env('LARAVEL_BOOTSTRAP_MODELS_AUTO_EAGER_LOAD', true),
        'send_violations_to_sentry' => env('LARAVEL_BOOTSTRAP_MODELS_SEND_VIOLATIONS_TO_SENTRY', true),
    ],

    'database' => [
        'default_string_length' => env('LARAVEL_BOOTSTRAP_DATABASE_DEFAULT_STRING_LENGTH', 255),
        'default_charset' => env('LARAVEL_BOOTSTRAP_DATABASE_DEFAULT_CHARSET', 'utf8mb4'),
        'default_collation' => env('LARAVEL_BOOTSTRAP_DATABASE_DEFAULT_COLLATION', 'utf8mb4_unicode_ci'),
        'prohibit_destructive_commands' => env('LARAVEL_BOOTSTRAP_PROHIBIT_DESTRUCTIVE_COMMANDS', true),
        'prohibit_destructive_commands_environments' => explode(',', env('LARAVEL_BOOTSTRAP_PROHIBIT_DESTRUCTIVE_COMMANDS_ENVIRONMENTS', 'production')),
    ],

    'dates' => [
        'handler' => Carbon\CarbonImmutable::class,
    ],

    'password' => [
        'apply_constraints_environments' => explode(',', env('LARAVEL_BOOTSTRAP_PASSWORD_APPLY_CONSTRAINTS_ENVIRONMENTS', 'production')),
        'min_length' => env('LARAVEL_BOOTSTRAP_PASSWORD_MIN_LENGTH', 8),
        'require_mixedcase' => env('LARAVEL_BOOTSTRAP_PASSWORD_REQUIRE_MIXEDCASE', true),
        'require_numeric' => env('LARAVEL_BOOTSTRAP_PASSWORD_REQUIRE_NUMERIC', true),
        'require_symbols' => env('LARAVEL_BOOTSTRAP_PASSWORD_REQUIRE_SYMBOLS', true),
        'require_uncompromised' => env('LARAVEL_BOOTSTRAP_PASSWORD_REQUIRE_UNCOMPROMISED', true),
    ],
];
