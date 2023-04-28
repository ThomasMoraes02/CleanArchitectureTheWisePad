<?php 
namespace CleanArchitecture\Application\UseCases\Auth;

interface TokenManager
{
    /**
     * @param array $payload
     * @return string
     */
    public function sign(array $payload): string;

    /**
     * @param string $token
     * @return boolean
     */
    public function verify(string $token): bool;
}