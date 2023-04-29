<?php 
namespace CleanArchitecture\Application\Exceptions;

use Exception;

class NoteNotFound extends Exception
{
    public function __construct()
    {
        parent::__construct("Not not found.");
    }
}