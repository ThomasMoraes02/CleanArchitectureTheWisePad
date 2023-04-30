<?php
namespace CleanArchitecture\Application\Middleware;

interface Middleware
{
    /**
     * Authenticate Middleware
     *
     * @param string|array|mixed|object $requestMiddleware
     * @return boolean
     */
    public function authenticate($requestMiddleware): bool;
}