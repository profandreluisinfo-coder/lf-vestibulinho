<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WithInscription
{
    /**
     * Se o usuário não estiver inscrito, ele NÃO pode acessar a rota. O mesmo será redirecionado para
     * área de preenchimento da ficha de inscrição.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->user()->inscription()->exists()) {
            return redirect()->route('dash.user.home');
        }

        return $next($request);
    }
}
