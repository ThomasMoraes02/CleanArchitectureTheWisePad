<?php 
namespace CleanArchitecture\Entities\User;

use CleanArchitecture\Entities\Email;
use CleanArchitecture\Entities\Encoder;

class User
{
    private string $name;

    private Email $email;

    private Encoder $encoder;

    public function __construct(string $name, Email $email, Encoder $encoder)
    {
        $this->name = $name;
        $this->email = $email;
        $this->encoder = $encoder;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getEncoder(): string
    {
        return $this->encoder;
    }
}