<?php

namespace App\Helpers;

class MenuHelper
{
    /**
     * Verifica se a rota atual pertence a um grupo de rotas
     */
    public static function isActiveMenu(array $routes): bool
    {
        return in_array(request()->route()->getName(), $routes);
    }

    /**
     * Retorna 'show' se o menu deve estar aberto
     */
    public static function menuState(array $routes): string
    {
        return self::isActiveMenu($routes) ? 'show' : '';
    }

    /**
     * Retorna 'active' se o link deve estar destacado
     */
    public static function activeLink(string|array $routes): string
    {
        $routeNames = is_array($routes) ? $routes : [$routes];
        return in_array(request()->route()->getName(), $routeNames) ? 'active' : '';
    }
}