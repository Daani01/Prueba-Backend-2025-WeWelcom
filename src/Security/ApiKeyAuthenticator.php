<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class ApiKeyAuthenticator extends AbstractAuthenticator
{
    private UserProviderInterface $userProvider;

    public function __construct(UserProviderInterface $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    public function supports(Request $request): ?bool
    {
        return $request->headers->has('Authorization') || $request->query->has('apikey');
    }

    public function authenticate(Request $request): SelfValidatingPassport
    {
        $apiKey = null;

        if ($request->headers->has('Authorization')) {
            $authHeader = $request->headers->get('Authorization');
            if (str_starts_with($authHeader, 'ApiKey ')) {
                $apiKey = substr($authHeader, 7);
            }
        }

        if (!$apiKey && $request->query->has('apikey')) {
            $apiKey = $request->query->get('apikey');
        }

        if (empty($apiKey)) {
            throw new AuthenticationException('No API key provided');
        }

        return new SelfValidatingPassport(
            new UserBadge($apiKey, function ($apiKey) {
                $user = $this->userProvider->loadUserByIdentifier($apiKey);
                if (!$user) {
                    throw new AuthenticationException('Invalid API key');
                }
                return $user;
            })
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new Response(json_encode([
            'error' => 'Authentication failed',
            'message' => $exception->getMessage()
        ]), Response::HTTP_UNAUTHORIZED, ['Content-Type' => 'application/json']);
    }
}
