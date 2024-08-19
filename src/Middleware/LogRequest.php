<?php

namespace PageviewCounter\Middleware;

use PageviewCounter\Models\Pageview;
use Illuminate\Http\Request;
use Closure;

class LogRequest
{
    public function handle(Request $request, Closure $next)
    {
        // Make it an after middleware
        $response = $next($request);

        try {
            Pageview::create([
                'path' => $request->path(),
                'method' => $request->method(),
                'useragent' => $request->userAgent(),
                'visitorid' => crypt($request->ip(), config('app.key')),
                'referer' => $request->headers->get('referer')
            ]);
            
            return $response;
        } catch (Error $e) {
            report($e);
            return $response;
        }
    }
}