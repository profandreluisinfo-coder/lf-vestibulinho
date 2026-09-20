<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureInstructionsConfirmed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! session('instructions_confirmed')) {
            return redirect()->route('inscription.start')
                ->with('error', 'Você precisa confirmar que leu as instruções antes de continuar.');
        }
        
        return $next($request);
    }
}
