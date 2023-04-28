<?php 
namespace CleanArchitecture\Entities\User;

use CleanArchitecture\Entities\Email;

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
     * Return All Users
     *
     * @return array
     */
    public function getAll(): array;
}