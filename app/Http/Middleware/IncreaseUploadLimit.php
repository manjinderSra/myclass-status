<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IncreaseUploadLimit
{
    public function handle(Request $request, Closure $next)
    {
        // Increase upload limits using PHP settings
        ini_set('upload_max_filesize', '50M');
        ini_set('post_max_size', '50M');
        ini_set('memory_limit', '256M');
        ini_set('max_execution_time', 120);
        
        return $next($request);
    }
} 