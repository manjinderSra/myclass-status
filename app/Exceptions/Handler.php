<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
        
        // Handle PostTooLargeException with a better error message
        $this->renderable(function (\Illuminate\Http\Exceptions\PostTooLargeException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'The file you are trying to upload is too large.',
                    'errors' => ['file' => ['The file size exceeds the allowed limit.']]
                ], 413);
            }
            
            return redirect()->back()->withErrors([
                'file' => 'The file you are trying to upload is too large. Maximum allowed size is 50MB.'
            ])->withInput();
        });
    }
}
