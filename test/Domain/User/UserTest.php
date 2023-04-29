<?php 
namespace CleanArchitecture\Test\Domain\User;

use CleanArchitecture\Domain\Email;
use CleanArchitecture\Domain\User\User;
use CleanArchitecture\Infraestructure\Encoder\EncoderArgonII;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testCreateUser()
    {
        $user = new User("Thomas Moraes", new Email("thomas@gmail.com"), new EncoderArgonII("123456"));

        $this->assertEquals("Thomas Moraes", $user->getName());
        $this->assertEquals("thomas@gmail.com", $user->getEmail());
        $this->assertNotEmpty($user->getEncoder());

        $this->assertTrue((new EncoderArgonII)->decode("123456", $user->getEncoder()));
    }
}