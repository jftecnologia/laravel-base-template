<?php

declare(strict_types = 1);

namespace App\Extensions\LaravelExceptions\Channels;

use JuniorFontenele\LaravelContext\Facades\LaravelContext;
use JuniorFontenele\LaravelExceptions\Contracts\ExceptionChannel;
use JuniorFontenele\LaravelExceptions\Exceptions\AppException;

class LaravelContextChannel implements ExceptionChannel
{
    public function send(\Throwable $exception, array $context): void
    {
        $laravelContextData = [
            'type' => get_class($exception),
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
        ];

        if ($exception instanceof AppException) {
            $laravelContextData['id'] = $exception->getErrorId();
        }

        LaravelContext::set('exception', $laravelContextData);
    }
}
