<?php 
namespace CleanArchitecture\Application\Exceptions;

use Exception;

class PermissionRefused extends Exception
{
    public function __construct(string $message = "Permissão negada a este recurso.")
    {
        parent::__construct($message);
    }
}