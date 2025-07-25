<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class ApiEntryPoint implements AuthenticationEntryPointInterface
{
    public function start(Request $request, AuthenticationException $authException = null): Response
{
    error_log("Entró a ApiEntryPoint::start: " . ($authException?->getMessage() ?? 'No Exception'));
    
    return new Response(json_encode([
        'error' => 'Authentication Required',
        'message' => $authException?->getMessage() ?? 'No API key provided.'
    ]), Response::HTTP_UNAUTHORIZED, ['Content-Type' => 'application/json']);
}

}
