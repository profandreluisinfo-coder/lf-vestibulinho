<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Process;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsResultEnabled
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $process = Process::current();

        if (! $process->isResultEnabled()) {
            abort(404);
        }
        
        return $next($request);
    }
}