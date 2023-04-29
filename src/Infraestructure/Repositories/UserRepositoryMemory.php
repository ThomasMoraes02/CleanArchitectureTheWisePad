<?php 
namespace CleanArchitecture\Infraestructure\Repositories;

use CleanArchitecture\Application\Exceptions\UserNotFound;
use CleanArchitecture\Domain\Email;
use CleanArchitecture\Domain\User\User;
use CleanArchitecture\Entities\User\UserRepository;

class UserRepositoryMemory implements UserRepository
{
    private array $users = [];

    /**
     * Add User to Repository
     *
     * @param User $user
     * @return void
     */
    public function add(User $user): void
    {
        $this->users[] = $user;
    }

    /**
     * Find User By Id
     *
     * @param Email $email
     * @return User
     */
    public function findByEmail(Email $email): User
    {
        $user = array_filter($this->users, fn($user) => $user->getEmail() == $email);

        if(empty($user)) {
            throw new UserNotFound;
        }

        return $user[0];
    }

    /**
     * Get All Users
     *
     * @return array
     */
    public function getAll(): array
    {
        return $this->users;
    }
}