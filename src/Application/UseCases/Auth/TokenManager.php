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
     * @param string $payload
     * @return array|object|bool
     */
    public function verify(string $payload);
}