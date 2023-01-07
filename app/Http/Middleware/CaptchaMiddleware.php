<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CaptchaMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }
}
