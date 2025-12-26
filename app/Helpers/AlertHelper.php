<?php

if (!function_exists('alertSuccess')) {
    function alertSuccess(string $message, $route = null)
    {
        return $route
            ? redirect()->route($route)->with('success', $message)
            : redirect()->back()->with('success', $message);
    }
}

if (!function_exists('alertError')) {
    function alertError(string $message, $route = null)
    {
        return $route
            ? redirect()->route($route)->with('error', $message)
            : redirect()->back()->with('error', $message);
    }
}

if (!function_exists('alertWarning')) {
    function alertWarning(string $message, $route = null)
    {
        return $route
            ? redirect()->route($route)->with('warning', $message)
            : redirect()->back()->with('warning', $message);
    }
}

if (!function_exists('alertInfo')) {
    function alertInfo(string $message, $route = null)
    {
        return $route
            ? redirect()->route($route)->with('info', $message)
            : redirect()->back()->with('info', $message);
    }
}