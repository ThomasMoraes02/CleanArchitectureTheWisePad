<?php 
namespace CleanArchitecture\Domain\User;

use CleanArchitecture\Domain\Email;
use CleanArchitecture\Domain\User\User;

interface UserRepository
{
    /**
     * Add User to Repository
     *
     * @param User $user
     * @return void
     */
    public function add(User $user): void;

    /**
     * Find User By Email
     *
     * @param Email $email
     * @return User
     */
    public function findByEmail(Email $email): User;

    /**
     * Find User By Id
     *
     * @param string $id
     * @return ?User
     */
    public function findUserById(string $id): ?User;

    /**
     * Get User Id
     *
     * @param User $user
     * @return string
     */
    public function getUserId(User $user): string;

    /**
     * Return All Users
     *
     * @return array
     */
    public function getAll(): array;
}