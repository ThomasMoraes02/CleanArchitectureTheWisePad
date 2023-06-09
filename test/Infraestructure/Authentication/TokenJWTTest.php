<?php 
namespace CleanArchitecture\Test\Infraestructure\Authentication;

use CleanArchitecture\Infraestructure\Authentication\TokenJWT;
use PHPUnit\Framework\TestCase;

class TokenJWTTest extends TestCase
{
    public function testCreateAccessToken()
    {
        $token = new TokenJWT;
        $accessToken = $token->sign(["email" => "thomas@gmail.com"]);

        $this->assertNotEmpty($accessToken);
    }

    public function testDecodeAccessToken()
    {
        $token = new TokenJWT;
        $accessToken = $token->sign(["email" => "thomas@gmail.com"]);
        $decode = $token->verify($accessToken);

        $this->assertEquals("thomas@gmail.com", $decode->payload->email);
    }
}