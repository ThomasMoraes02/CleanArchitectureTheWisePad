<?php 
namespace CleanArchitecture\Test\Infraestructure\Repositories;

use CleanArchitecture\Domain\Email;
use PHPUnit\Framework\TestCase;
use CleanArchitecture\Domain\User\User;
use CleanArchitecture\Infraestructure\Encoder\EncoderArgonII;
use CleanArchitecture\Infraestructure\Repositories\UserRepositoryMemory;

class UserRepositoryMemoryTest extends TestCase
{
    private User $user;

    private UserRepositoryMemory $userRepository;

    protected function setUp(): void
    {
        $this->user = new User("Thomas", new Email("thomas@gmail.com"), new EncoderArgonII("123456"));
        $this->userRepository = new UserRepositoryMemory;
    }

    public function testAddUser()
    {
        $this->userRepository->add($this->user);
        $this->assertNotEmpty($this->userRepository);
    }

    public function testFindUser()
    {
        $this->userRepository->add($this->user);
        
        $this->assertNotEmpty($this->userRepository->findByEmail($this->user->getEmail()));

        $this->assertEquals(1, count($this->userRepository->getAll()));
    }
}