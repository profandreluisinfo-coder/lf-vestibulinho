<?php

namespace App\Exceptions\Http;

use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Symfony\Component\HttpFoundation\Response;

class ThrottleRequestsExceptionHandler
{
    public function render(ThrottleRequestsException $e, $request): Response
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Você excedeu o número de requisições permitidas. Por favor, aguarde antes de tentar novamente.'
            ], Response::HTTP_TOO_MANY_REQUESTS);
        }

        return response()->view('errors.429', [], Response::HTTP_TOO_MANY_REQUESTS);
    }
}