<?php 
namespace CleanArchitecture\Domain;

use DomainException;

class Email
{
    /** @var string $email */
    private string $email;

    public function __construct(string $email)
    {
        $this->setEmail($email);
    }

    /**
     * Verify e-mail
     *
     * @param string $email
     * @return void
     */
    private function setEmail(string $email): void
    {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new DomainException("Este e-mail é inválido: {$email}");
        }

        $this->email = $email;
    }

    /**
     * Return e-mail
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->email;
    }
}