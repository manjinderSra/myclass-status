<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }

        return match (true) {
            $request->is('admin', 'admin/*') => route('saasAdmin.login'),
            $request->is('student', 'student/*') => route('student.login'),
            $request->is('teacher', 'teacher/*') => route('teacher.login'),
            default => route('school.login'),
        };
    }
}
