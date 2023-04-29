<?php 
namespace CleanArchitecture\Application\Middleware;

use CleanArchitecture\Application\Helpers\HttpStatusHelper;
use CleanArchitecture\Application\UseCases\Auth\TokenManager;

class Authentication
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
     * @param string $accessToken
     * @return boolean
     */
    public function authenticate(string $accessToken): bool
    {
        $accessToken = str_replace("Bearer ", "", $accessToken);
        return $this->tokenManager->verify($accessToken);
    }
}