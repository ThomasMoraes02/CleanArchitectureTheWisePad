<?php 
namespace CleanArchitecture\Application\UseCases\Auth;

use CleanArchitecture\Application\UseCases\UseCase;

class SignIn implements UseCase
{
    private AuthenticationService $authentication;

    public function __construct(AuthenticationService $authentication)
    {
        $this->authentication = $authentication;
    }
    
    public function execute(array $request): array
    {
        $response = $this->authentication->auth($request);
        return $response;
    }
}