<?php 
namespace CleanArchitecture\Infraestructure\Repositories;

use MongoDB\Client;
use MongoDB\Collection;
use CleanArchitecture\Domain\Email;
use CleanArchitecture\Domain\Encoder;
use CleanArchitecture\Domain\User\User;
use MongoDB\Operation\FindOneAndUpdate;
use CleanArchitecture\Domain\User\UserRepository;
use CleanArchitecture\Application\Exceptions\UserNotFound;
use CleanArchitecture\Infraestructure\Repositories\MongoDB\MongoDb;
use MongoDB\Database;

class UserRepositoryMongodb implements UserRepository
{
    private Database $client;

    private Collection $userCollection;

    private string $collection = "users";

    private Encoder $encoder;

    public function __construct(Encoder $encoder)
    {
        $this->encoder = $encoder;
        $this->client = (new Client())->selectDatabase("thewisepad");
        $this->userCollection = $this->client->selectCollection($this->collection);
    }

    public function add(User $user): void
    {
        $id = $this->getNextId();

        $document = [
            "_id" => $id,
            "name" => $user->getName(),
            "email" => $user->getEmail()->__toString(),
            "password" => $user->getEncoder()->__toString()
        ];

        $this->userCollection->insertOne($document);
    }

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

    public function findUserById(string $id): ?User
    {
        $findUser = $this->userCollection->find(['_id' => $id])->toArray();
        $findUser = current($findUser);

        if(empty($findUser)) {
            return null;
        }

        $encoder = $this->encoder;
        return new User($findUser['name'], new Email($findUser['email']), new $encoder($findUser['password']));
    }

    public function getUserId(User $user): string
    {
        $findUser = $this->userCollection->find(['email' => $user->getEmail()->__toString()])->toArray();
        $findUser = current($findUser);

        if(empty($findUser)) {
            throw new UserNotFound;
        }

        return $findUser['_id'];
    }

    public function getAll(): array
    {
        $users = $this->userCollection->find()->toArray();
        return $users;
    }

    /**
     * Generate Next ID
     *
     * @return string
     */
    private function getNextId(): string
    {
        $counters = $this->client->selectCollection("users_counters");

        $result = $counters->findOneAndUpdate(
            ["_id", "id"],
            ['$inc' => ['seq' => 1]],
            ['upsert' => true, 'projection' => [ 'seq' => 1 ],'returnDocument' => FindOneAndUpdate::RETURN_DOCUMENT_AFTER]
        );
        
        return $result["seq"];
    }
}