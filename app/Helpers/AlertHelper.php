<?php

if (!function_exists('alertSuccess')) {
    /**
     * Redireciona com mensagem de sucesso
     *
     * @param string $message mensagem de sucesso
     * @param string|null $route rota para redirecionar (opcional)
     * @return \Illuminate\Http\RedirectResponse
     */
    function alertSuccess(string $message, $route = null)
    {
        return $route
            ? redirect()->route($route)->with('success', $message)
            : redirect()->back()->with('success', $message);
    }
}

if (!function_exists('alertError')) {
    /*
     * Redireciona com mensagem de erro
     *
     * @param string $message mensagem de erro
     * @param string|null $route rota para redirecionar (opcional)
     * @return \Illuminate\Http\RedirectResponse
     */
    function alertError(string $message, $route = null)
    {
        return $route
            ? redirect()->route($route)->with('error', $message)
            : redirect()->back()->with('error', $message);
    }
}

if (!function_exists('alertWarning')) {
    /**
     * Redireciona com mensagem de alerta
     *
     * @param string $message mensagem de alerta
     * @param string|null $route rota para redirecionar (opcional)
     * @return \Illuminate\Http\RedirectResponse
     */
    function alertWarning(string $message, $route = null)
    {
        return $route
            ? redirect()->route($route)->with('warning', $message)
            : redirect()->back()->with('warning', $message);
    }
}

if (!function_exists('alertInfo')) {
    /**
     * Redireciona com mensagem de informação
     *
     * @param string $message mensagem de informação
     * @param string|null $route rota para redirecionar (opcional)
     * @return \Illuminate\Http\RedirectResponse
     */
    function alertInfo(string $message, $route = null)
    {
        return $route
            ? redirect()->route($route)->with('info', $message)
            : redirect()->back()->with('info', $message);
    }
}