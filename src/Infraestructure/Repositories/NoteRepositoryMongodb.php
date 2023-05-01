<?php 
namespace CleanArchitecture\Infraestructure\Repositories;

use CleanArchitecture\Application\Exceptions\NoteNotFound;
use MongoDB\Client;
use MongoDB\Database;
use MongoDB\Collection;
use CleanArchitecture\Domain\Email;
use CleanArchitecture\Domain\Note\Note;
use MongoDB\Operation\FindOneAndUpdate;
use CleanArchitecture\Domain\Note\Title;
use CleanArchitecture\Domain\Note\NoteRepository;
use CleanArchitecture\Domain\User\UserRepository;

class NoteRepositoryMongodb implements NoteRepository
{
    private Database $client;

    private Collection $noteCollection;

    private string $collection = "notes";

    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->client = (new Client())->selectDatabase("thewisepad");
        $this->noteCollection = $this->client->selectCollection($this->collection);
    }

    public function add(Note $note): void
    {
        $id = $this->getNextId();

        $document = [
            "_id" => $id,
            "title" => $note->getTitle()->__toString(),
            "content" => $note->getContent(),
            "user_email" => $note->getUser()->getEmail()->__toString()
        ];

        $this->noteCollection->insertOne($document);
    }

    public function findById(string $id): Note
    {
        $note = $this->noteCollection->find(['_id' => $id])->toArray();
        $note = current($note);

        if(empty($note)) {
            return null;
        }

        $user = $this->userRepository->findByEmail(new Email($note['user_email']));
        return new Note($user, new Title($note['title']), $note['content']);
    }

    public function findyByTitle(Title $title): Note
    {
        $note = $this->noteCollection->find(['title' => $title->__toString()])->toArray();
        $note = current($note);

        if(empty($note)) {
            return null;
        }

        $user = $this->userRepository->findByEmail(new Email($note['user_email']));
        return new Note($user, new Title($note['title']), $note['content']);
    }

    public function findAllNotesFrom(Email $email, int $page = 0, int $per_page = 0): array
    {
       return $this->noteCollection->find(["user_email" => $email->__toString()])->toArray();
    }

    public function update(string $id, array $data): void
    {
        
    }

    public function delete(string $id): void
    {
        $note = $this->noteCollection->find(['_id' => $id])->toArray();

        if(empty($note)) {
            throw new NoteNotFound;
        }

        $this->noteCollection->deleteOne(["_id" => $id]);
    }

     /**
     * Generate Next ID
     *
     * @return string
     */
    private function getNextId(): string
    {
        $counters = $this->client->selectCollection("note_counters");

        $result = $counters->findOneAndUpdate(
            ["_id", "id"],
            ['$inc' => ['seq' => 1]],
            ['upsert' => true, 'projection' => [ 'seq' => 1 ],'returnDocument' => FindOneAndUpdate::RETURN_DOCUMENT_AFTER]
        );
        
        return $result["seq"];
    }
}