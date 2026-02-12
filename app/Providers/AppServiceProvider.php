<?php

declare(strict_types = 1);

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

use Sentry\Laravel\Integration;

class AppServiceProvider extends ServiceProvider
{
    private array $context = [];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->registerCustomClasses();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->setupHttpRequestScheme();
        $this->setupModelsSettings();
        $this->setupDatabaseSettings();
        $this->setupCommandsSettings();
        $this->setupDatesSettings();
        $this->setupPasswordRequirements();
    }

    private function setupHttpRequestScheme(): void
    {
        if (config('app.force_https', ! app()->isLocal())) {
            URL::forceScheme('https');

            return;
        }

        if (request()->isSecure()) {
            URL::forceScheme('https');
        }
    }

    private function setupModelsSettings(): void
    {
        Model::unguard();
        Model::automaticallyEagerLoadRelationships();

        if (app()->isProduction()) {
            Model::handleLazyLoadingViolationUsing(Integration::lazyLoadingViolationReporter());
            Model::handleDiscardedAttributeViolationUsing(Integration::discardedAttributeViolationReporter());
            Model::handleMissingAttributeViolationUsing(Integration::missingAttributeViolationReporter());
        }
    }

    private function setupDatabaseSettings(): void
    {
        Schema::defaultStringLength(255);

        Blueprint::macro('defaultCharset', function () {
            /**
             * @var Blueprint $this
             */
            $this->charset = 'utf8mb4';
            $this->collation = 'utf8mb4_0900_ai_ci';

            return $this;
        });
    }

    private function setupCommandsSettings(): void
    {
        DB::prohibitDestructiveCommands(app()->isProduction());
    }

    private function setupDatesSettings(): void
    {
        Date::use(CarbonImmutable::class);
    }

    private function setupPasswordRequirements(): void
    {
        Password::defaults(function () {
            if (! app()->isProduction()) {
                return null;
            }

            return Password::min(8)
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised();
        });
    }

    private function registerCustomClasses(): void
    {
        //
    }
}
