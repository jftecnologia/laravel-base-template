<?php

declare(strict_types = 1);

namespace App\Extensions\ActivityLog;

use App\Enums\LogLevel;
use Spatie\Activitylog\Contracts\Activity as ActivityContract;

class ActivityLogger extends \Spatie\Activitylog\ActivityLogger
{
    protected LogLevel $logLevel = LogLevel::INFORMATIONAL;

    public function logLevel(LogLevel $logLevel): static
    {
        $this->logLevel = $logLevel;

        return $this;
    }

    public function level(LogLevel $logLevel): static
    {
        return $this->logLevel($logLevel);
    }

    public function log(string $description): ?ActivityContract
    {
        $this->tap(function ($activity) {
            $activity->log_level = $this->logLevel;
        });

        return parent::log($description);
    }
}
