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
        $expires = time() + 60 * 60 * 24 * 1;
        
        $payloadJWT = [
            "iss" => "secret-token-api-2023",
            "exp" => $expires,
            "name" => $payload,
            "role" => "user"
        ];

        return JWT::encode($payloadJWT, "secret-token-api-2023", "HS256");
    }

    /**
     * Decode and Verify Access Token JWT
     *
     * @param string $token
     * @return boolean
     */
    public function verify(string $token): bool
    {
        $decode = JWT::decode($token, new Key("secret-token-api-2023", "HS256"));
        
        $expires = new DateTime(date("Y-m-d", $decode->exp));
        $interval = $expires->diff(new DateTime());

        if($interval->days > 1) {
            return false;
        }
        return true;
    }
}