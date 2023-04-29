<?php 
namespace CleanArchitecture\Infraestructure\Encoder;

use CleanArchitecture\Domain\Encoder;

class EncoderArgonII implements Encoder
{
    private string $password;

    public function __construct(string $password)
    {
        $this->password = password_get_info($password)['algo'] ? $this->encode($password) : $password;
    }

    /**
     * Encode Password Argon2ID
     *
     * @param string $password
     * @return string
     */
    public function encode(string $password): string
    {
        return password_hash($password, PASSWORD_ARGON2ID);
    }

    /**
     * Decode Password
     *
     * @param string $password
     * @param string $hash
     * @return boolean
     */
    public function decode(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->password;
    }
}