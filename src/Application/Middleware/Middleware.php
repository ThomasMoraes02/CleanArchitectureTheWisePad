<?php
namespace CleanArchitecture\Application\Middleware;

interface Middleware
{
    /**
     * Authenticate Middleware
     *
     * @param string|array|mixed|object $requestMiddleware
     * @return string
     */
    public function authenticate($requestMiddleware): string;
}