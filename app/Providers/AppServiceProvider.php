<?php

declare(strict_types = 1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Activitylog\PendingActivityLog;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PendingActivityLog::class, function ($app) {
            return new \App\Extensions\ActivityLog\PendingActivityLog(
                $app->make(\Spatie\Activitylog\ActivityLogger::class),
                $app->make(\Spatie\Activitylog\ActivityLogStatus::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
