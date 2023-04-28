<?php 
namespace CleanArchitecture\Domain\User;

use CleanArchitecture\Domain\Email;
use CleanArchitecture\Domain\Encoder;

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
     * @return Email
     */
    public function getEmail(): Email
    {
        return $this->email;
    }

    /**
     * @return Encoder
     */
    public function getEncoder(): Encoder
    {
        return $this->encoder;
    }
}