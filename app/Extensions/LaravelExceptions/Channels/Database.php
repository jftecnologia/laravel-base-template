<?php

declare(strict_types = 1);

namespace App\Extensions\LaravelExceptions\Channels;

use Illuminate\Support\Facades\Log;
use JuniorFontenele\LaravelContext\Facades\LaravelContext;
use JuniorFontenele\LaravelExceptions\Contracts\ExceptionChannel;
use JuniorFontenele\LaravelExceptions\Exceptions\AppException;
use JuniorFontenele\LaravelExceptions\Models\Exception;
use JuniorFontenele\LaravelTracing\Facades\LaravelTracing;
use Throwable;

class Database implements ExceptionChannel
{
    public function send(Throwable $exception, array $context): void
    {
        try {
            $exceptionData = [
                'exception_class' => get_class($exception),
                'message' => $exception->getMessage(),
                'user_message' => $exception instanceof AppException ? $exception->getUserMessage() : null,
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'code' => $exception->getCode(),
                'status_code' => $exception instanceof AppException ? $exception->getStatusCode() : null,
                'error_id' => $exception instanceof AppException ? $exception->getErrorId() : null,
                'app_env' => LaravelContext::get('app.env'),
                'app_debug' => LaravelContext::get('app.debug'),
                'app_name' => LaravelContext::get('app.name'),
                'app_version' => LaravelContext::get('app.version'),
                'app_commit' => LaravelContext::get('app.commit'),
                'host_name' => LaravelContext::get('host.name'),
                'host_ip' => LaravelContext::get('host.ip'),
                'user_id' => LaravelContext::get('user.id'),
                'request_id' => LaravelTracing::get('request_id'),
                'correlation_id' => LaravelTracing::get('correlation_id'),
                'is_retryable' => $exception instanceof AppException ? $exception->isRetryable() : null,
                'stack_trace' => $exception->getTraceAsString(),
                'previous_exception_class' => $exception->getPrevious() ? get_class($exception->getPrevious()) : null,
                'previous_message' => $exception->getPrevious() ? $exception->getPrevious()->getMessage() : null,
                'previous_file' => $exception->getPrevious() ? $exception->getPrevious()->getFile() : null,
                'previous_line' => $exception->getPrevious() ? $exception->getPrevious()->getLine() : null,
                'previous_code' => $exception->getPrevious() ? $exception->getPrevious()->getCode() : null,
                'previous_stack_trace' => $exception->getPrevious() ? $exception->getPrevious()->getTraceAsString() : null,
            ];

            Exception::create($exceptionData);
        } catch (Throwable $e) {
            // Silently fail to avoid breaking the application
            Log::error('Failed to save exception to database', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
