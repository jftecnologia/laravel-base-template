<?php

declare(strict_types = 1);

namespace App\Providers;

use Carbon\CarbonImmutable;
use Date;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Sentry\Laravel\Integration;

class BootstrapProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
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
        if (app()->runningInConsole()) {
            return;
        }

        $forceHttps = config('laravel-bootstrap.http.force_https');
        $enviroments = config('laravel-bootstrap.http.force_https_environments', []);

        if ($forceHttps && in_array($this->app->environment(), $enviroments, true)) {
            URL::forceScheme('https');

            return;
        }

        if (request()->isSecure()) {
            URL::forceScheme('https');
        }
    }

    private function setupModelsSettings(): void
    {
        if (config('laravel-bootstrap.models.unguarded')) {
            Model::unguard();
        }

        if (config('laravel-bootstrap.models.auto_eager_load')) {
            Model::automaticallyEagerLoadRelationships();
        }

        if (config('laravel-bootstrap.models.send_violations_to_sentry') && app()->isProduction()) {
            Model::handleLazyLoadingViolationUsing(Integration::lazyLoadingViolationReporter());
            Model::handleDiscardedAttributeViolationUsing(Integration::discardedAttributeViolationReporter());
            Model::handleMissingAttributeViolationUsing(Integration::missingAttributeViolationReporter());
        }
    }

    private function setupDatabaseSettings(): void
    {
        Schema::defaultStringLength(config('laravel-bootstrap.database.default_string_length', 255));

        Blueprint::macro('defaultCharset', function () {
            /**
             * @var Blueprint $this
             */
            $this->charset = config('laravel-bootstrap.database.default_charset', 'utf8mb4');
            $this->collation = config('laravel-bootstrap.database.default_collation', 'utf8mb4_unicode_ci');

            return $this;
        });
    }

    private function setupCommandsSettings(): void
    {
        if (! config('laravel-bootstrap.database.prohibit_destructive_commands')) {
            return;
        }

        $shouldProhibit = in_array(
            $this->app->environment(),
            config('laravel-bootstrap.database.prohibit_destructive_commands_environments', ['production']),
            true
        );

        DB::prohibitDestructiveCommands($shouldProhibit);
    }

    private function setupDatesSettings(): void
    {
        Date::use(config('laravel-bootstrap.dates.handler', CarbonImmutable::class));
    }

    private function setupPasswordRequirements(): void
    {
        Password::defaults(function () {
            $shouldApplyConstraints = in_array(
                $this->app->environment(),
                config('laravel-bootstrap.password.apply_constraints_environments', ['production']),
                true
            );

            if (! $shouldApplyConstraints) {
                return null;
            }

            $passwordConstraints = Password::min(config('laravel-bootstrap.password.min_length', 8));

            if (config('laravel-bootstrap.password.require_mixedcase', true)) {
                $passwordConstraints->mixedCase();
            }

            if (config('laravel-bootstrap.password.require_numeric', true)) {
                $passwordConstraints->numbers();
            }

            if (config('laravel-bootstrap.password.require_symbols', true)) {
                $passwordConstraints->symbols();
            }

            if (config('laravel-bootstrap.password.require_uncompromised', true)) {
                $passwordConstraints->uncompromised();
            }

            return $passwordConstraints;
        });
    }
}
