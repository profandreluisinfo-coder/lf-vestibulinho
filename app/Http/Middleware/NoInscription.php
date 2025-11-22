<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NoInscription
{
    /**
     * Se o usuário não estiver inscrito, ele pode acessar a rota. Senão, será redirecionado para a área 
     * do candidato.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()->inscription()->exists()) {
            return redirect()->route('inscription.profile');
        }

        return $next($request);
    }
}