<?php 
namespace CleanArchitecture\Infraestructure\Repositories\Mongo;

use MongoDB\Collection;
use CleanArchitecture\Domain\Email;
use CleanArchitecture\Domain\Encoder;
use CleanArchitecture\Domain\User\User;
use CleanArchitecture\Domain\User\UserRepository;
use CleanArchitecture\Application\Exceptions\UserNotFound;
use CleanArchitecture\Infraestructure\Repositories\Mongo\MongoDBConnection;

class UserRepositoryMongodb implements UserRepository
{
    private MongoDBConnection $mongoConnect;

    private Collection $userCollection;

    private Encoder $encoder;

    public function __construct(Encoder $encoder, MongoDBConnection $mongoConnect)
    {
        $this->encoder = $encoder;

        $this->mongoConnect = $mongoConnect;
        $this->userCollection = $mongoConnect->getDatabase()->selectCollection("users");
    }

    /**
     * Add User to Repository
     *
     * @param User $user
     * @return void
     */
    public function add(User $user): void
    {
        $id = $this->mongoConnect->getNextId("users_counters");

        $document = [
            "id" => $id,
            "name" => $user->getName(),
            "email" => $user->getEmail()->__toString(),
            "password" => $user->getEncoder()->__toString()
        ];

        $this->userCollection->insertOne($document);
    }

    /**
     * Find User By Email
     *
     * @param Email $email
     * @return User
     */
    public function findByEmail(Email $email): User
    {
        $findUser = $this->userCollection->find(['email' => $email->__toString()])->toArray();
        $findUser = current($findUser);

        if(empty($findUser)) {
            throw new UserNotFound;
        }

        $encoder = $this->encoder;
        return new User($findUser->name, new Email($findUser->email), new $encoder($findUser->password));
    }

    /**
     * Find User By Id
     *
     * @param string $id
     * @return User|null
     */
    public function findUserById(string $id): ?User
    {
        $findUser = $this->userCollection->find(['id' => $id])->toArray();
        $findUser = current($findUser);

        if(empty($findUser)) {
            return null;
        }

        $encoder = $this->encoder;
        return new User($findUser['name'], new Email($findUser['email']), new $encoder($findUser['password']));
    }

    /**
     * Get User By ID
     *
     * @param User $user
     * @return string
     */
    public function getUserId(User $user): string
    {
        $findUser = $this->userCollection->find(['email' => $user->getEmail()->__toString()])->toArray();
        $findUser = current($findUser);

        if(empty($findUser)) {
            throw new UserNotFound;
        }

        return $findUser['id'];
    }

    /**
     * Get All Users
     *
     * @return array
     */
    public function getAll(): array
    {
        $users = $this->userCollection->find()->toArray();
        return $users;
    }
}