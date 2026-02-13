<?php

declare(strict_types = 1);

namespace App\Extensions\ActivityLog;

use Illuminate\Support\Traits\ForwardsCalls;
use JuniorFontenele\LaravelTracing\Facades\LaravelTracing;
use Spatie\Activitylog\ActivityLogger;
use Spatie\Activitylog\ActivityLogStatus;
use Spatie\Activitylog\Models\Activity;

/**
 * @mixin \Spatie\Activitylog\ActivityLogger
 */
class PendingActivityLog
{
    use ForwardsCalls;

    protected ActivityLogger $logger;

    public function __construct(ActivityLogger $logger, ActivityLogStatus $status)
    {
        $this->logger = $logger
            ->setLogStatus($status)
            ->useLog(config('activitylog.default_log_name'))
            ->tap(function (Activity $activity) {
                $activity->request_id = LaravelTracing::get('request_id');
                $activity->correlation_id = LaravelTracing::get('correlation_id');
            });
    }

    public function logger(): ActivityLogger
    {
        return $this->logger;
    }

    public function __call(string $method, array $parameters): mixed
    {
        return $this->forwardCallTo($this->logger, $method, $parameters);
    }
}
