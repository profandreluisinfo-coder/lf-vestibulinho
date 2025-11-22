<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WithInscription
{
    /**
     * Se o usuário não estiver inscrito, ele não pode acessar a rota. Senão, será redirecionado para
     * área de preenchimento de ficha de inscrição.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->user()->inscription()->exists()) {
            return redirect()->route('dashboard.index');
        }

        return $next($request);
    }
}
