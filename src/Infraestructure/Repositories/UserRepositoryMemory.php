<?php 
namespace CleanArchitecture\Infraestructure\Repositories;

use CleanArchitecture\Domain\Email;
use CleanArchitecture\Domain\User\User;
use CleanArchitecture\Domain\User\UserRepository;
use CleanArchitecture\Application\Exceptions\UserNotFound;
use CleanArchitecture\Infraestructure\Encoder\EncoderArgonII;

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

        return current($user);
    }

    /**
     * Find User By Id
     *
     * @param string $id
     * @return User|null
     */
    public function findUserById(string $id): ?User
    {
        $user = array_filter($this->users, fn($user) => $this->getUserId($user) == $id);

        if(empty($user)) {
            return null;
        }

        return current($user);
    }

    /**
     * Get User Id
     *
     * @param User $user
     * @return string
     */
    public function getUserId(User $user): string
    {
        return key(array_filter($this->users, fn($user) => $user->getEmail()->__toString() == $user->getEmail()->__toString()));
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