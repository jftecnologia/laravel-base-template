<?php

declare(strict_types = 1);

namespace App\Enums;

enum LogLevel: int
{
    /**
     * System is unusable.
     */
    case EMERGENCY = 0;

    /**
     * Action must be taken immediately.
     */
    case ALERT = 1;

    /**
     * Critical conditions.
     */
    case CRITICAL = 2;

    /**
     * Error conditions.
     */
    case ERROR = 3;

    /**
     * Warning conditions.
     */
    case WARNING = 4;

    /**
     * Normal but significant condition.
     */
    case NOTICE = 5;

    /**
     * Informational messages.
     */
    case INFORMATIONAL = 6;

    /**
     * Debug-level messages.
     */
    case DEBUG = 7;

    /**
     * Returns the levels ordered by priority (highest priority first).
     *
     * @return list<self>
     */
    public static function byPriority(): array
    {
        return [
            self::EMERGENCY,
            self::ALERT,
            self::CRITICAL,
            self::ERROR,
            self::WARNING,
            self::NOTICE,
            self::INFORMATIONAL,
            self::DEBUG,
        ];
    }

    /**
     * Checks if this level is more critical than another (lower value means higher priority).
     */
    public function isMoreCriticalThan(self $other): bool
    {
        return $this->value < $other->value;
    }

    /**
     * Checks if this level is equal to or more critical than another.
     */
    public function isAtLeast(self $level): bool
    {
        return $this->value <= $level->value;
    }

    /**
     * Maps to PSR-3 log levels (useful in Laravel/Monolog).
     */
    public function toPsrLevel(): string
    {
        return match ($this) {
            self::EMERGENCY => 'emergency',
            self::ALERT => 'alert',
            self::CRITICAL => 'critical',
            self::ERROR => 'error',
            self::WARNING => 'warning',
            self::NOTICE => 'notice',
            self::INFORMATIONAL => 'info',
            self::DEBUG => 'debug',
        };
    }

    /**
     * Creates an instance from a PSR-3 log level (e.g., "error", "warning", etc).
     */
    public static function fromPsrLevel(string $level): self
    {
        return match (strtolower($level)) {
            'emergency' => self::EMERGENCY,
            'alert' => self::ALERT,
            'critical' => self::CRITICAL,
            'error' => self::ERROR,
            'warning' => self::WARNING,
            'notice' => self::NOTICE,
            'info', 'informational' => self::INFORMATIONAL,
            'debug' => self::DEBUG,
            default => throw new \InvalidArgumentException("Invalid PSR log level: {$level}"),
        };
    }
}
