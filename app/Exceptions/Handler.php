<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontReport = [
        //
    ];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        //
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof NotFoundHttpException) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Resource not found.'], 404);
            }

            return response()->view('errors.404', [], 404);
        }

        return new Response(
            "ORIGINAL LARAVEL ERROR\n\n" .
            "TYPE: " . get_class($e) . "\n\n" .
            "MESSAGE: " . $e->getMessage() . "\n\n" .
            "FILE: " . $e->getFile() . "\n" .
            "LINE: " . $e->getLine() . "\n\n" .
            "STACK TRACE:\n" . $e->getTraceAsString(),
            500,
            ['Content-Type' => 'text/plain']
        );
    }
}
