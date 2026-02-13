<?php

declare(strict_types = 1);

namespace App\Extensions\ActivityLog;

use App\Enums\LogLevel;

class Activity extends \Spatie\Activitylog\Models\Activity
{
    protected $casts = [
        'properties' => 'collection',
        'log_level' => LogLevel::class,
    ];
}
