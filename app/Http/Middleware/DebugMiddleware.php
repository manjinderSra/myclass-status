<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class DebugMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Log the request information before processing
        Log::info('INCOMING REQUEST', [
            'uri' => $request->fullUrl(),
            'method' => $request->method(),
            'ip' => $request->ip(),
            'request_content_type' => $request->header('Content-Type'),
            'user_agent' => $request->userAgent(),
            'input' => $request->all()
        ]);
        
        // Process the request
        $response = $next($request);
        
        // Log response information
        Log::info('OUTGOING RESPONSE', [
            'status' => $response->getStatusCode(),
            'content_type' => $response->headers->get('Content-Type')
        ]);
        
        return $response;
    }
}
