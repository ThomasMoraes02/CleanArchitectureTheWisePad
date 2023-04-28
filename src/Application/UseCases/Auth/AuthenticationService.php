<?php 
namespace CleanArchitecture\Application\UseCases\Auth;

interface AuthenticationService
{
    /**
     * Auth User
     *
     * @param array $authenticationParams
     * @return array
     */
    public function auth(array $authenticationParams): array;
}