<?php 
namespace CleanArchitecture\Infraestructure\Authentication;

use Firebase\JWT\JWT;
use CleanArchitecture\Application\UseCases\Auth\TokenManager;
use DateTime;
use Firebase\JWT\Key;

class TokenJWT implements TokenManager
{
    /**
     * Create Access Token JWT
     *
     * @param array $payload
     * @return string
     */
    public function sign(array $payload): string
    {
        $expires = time() + 60 * 60 * 24 * AUTH_EXPIRATION_TOKEN;
        
        $payloadJWT = [
            "iss" => AUTH_SECRET_KEY,
            "exp" => $expires,
            "payload" => $payload,
            "role" => "user"
        ];

        return JWT::encode($payloadJWT, AUTH_SECRET_KEY, AUTH_ALGORITHM);
    }

    /**
     * Decode and Verify Access Token JWT
     *
     * @param string $token
     * @return array|object|bool
     */
    public function verify(string $token)
    {
        return JWT::decode($token, new Key(AUTH_SECRET_KEY, AUTH_ALGORITHM));
    }
}