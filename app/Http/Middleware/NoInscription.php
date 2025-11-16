<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NoInscription
{
    /**
     * Se o usuário não estiver inscrito, ele pode acessar a rota. Senão, será redirecionado para a tela de inscrição.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()->inscription()->exists()) {
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}