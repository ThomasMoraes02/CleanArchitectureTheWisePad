<?php 
namespace CleanArchitecture\Application\Middleware;

use CleanArchitecture\Application\Helpers\HttpStatusHelper;
use CleanArchitecture\Application\UseCases\Auth\TokenManager;
use Exception;

class Authentication implements Middleware
{
    use HttpStatusHelper;

    private TokenManager $tokenManager;

    public function __construct(TokenManager $tokenManager)
    {
        $this->tokenManager = $tokenManager;
    }

    /**
     * Verify Access Token
     *
     * @param array|object|string $requestMiddleware
     * @return boolean
     * @throws Exception
     */
    public function authenticate($requestMiddleware): bool
    {
        if(empty($requestMiddleware)) {
            throw new Exception("Token de Autenticação precisa ser enviado.");
        }

        $accessToken = str_replace("Bearer ", "", $requestMiddleware);
        return $this->tokenManager->verify($accessToken);
    }
}