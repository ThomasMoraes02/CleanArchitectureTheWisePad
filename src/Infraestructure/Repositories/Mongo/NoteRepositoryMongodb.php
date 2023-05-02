<?php 
namespace CleanArchitecture\Infraestructure\Repositories\Mongo;

use CleanArchitecture\Application\Exceptions\NoteNotFound;
use MongoDB\Collection;
use CleanArchitecture\Domain\Email;
use CleanArchitecture\Domain\Note\Note;
use CleanArchitecture\Domain\Note\Title;
use CleanArchitecture\Domain\Note\NoteRepository;
use CleanArchitecture\Domain\User\UserRepository;
use CleanArchitecture\Infraestructure\Repositories\Mongo\MongoDBConnection;

class NoteRepositoryMongodb implements NoteRepository
{
    private MongoDBConnection $mongoConnect;

    private Collection $noteCollection;

    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository, MongoDBConnection $mongoConnect)
    {
        $this->userRepository = $userRepository;
        
        $this->mongoConnect = $mongoConnect;
        $this->noteCollection = $mongoConnect->getDatabase()->selectCollection("notes");
    }

    /**
     * Add Note to Repository
     *
     * @param Note $note
     * @return void
     */
    public function add(Note $note): void
    {
        $id = $this->mongoConnect->getNextId("note_counters");

        $document = [
            "id" => $id,
            "title" => $note->getTitle()->__toString(),
            "content" => $note->getContent(),
            "user_email" => $note->getUser()->getEmail()->__toString()
        ];

        $this->noteCollection->insertOne($document);
    }

    /**
     * Find Note by Id
     *
     * @param string $id
     * @return Note
     */
    public function findById(string $id): Note
    {
        $note = $this->noteCollection->find(['id' => $id])->toArray();
        $note = current($note);

        if(empty($note)) {
            throw new NoteNotFound;
        }

        $user = $this->userRepository->findByEmail(new Email($note['user_email']));
        return new Note($user, new Title($note['title']), $note['content']);
    }

    /**
     * Find Note By Title
     *
     * @param Title $title
     * @return Note
     */
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

    /**
     * Find All Notes From User Email
     *
     * @param Email $email
     * @param integer $page
     * @param integer $limit
     * @return array
     */
    public function findAllNotesFrom(Email $email, int $page = 0, int $limit = 0): array
    {
        $skip = ($page - 1) * $limit;
        $skip = ($skip < 0) ? 0 : $skip;

        $options = [
            'skip' => $skip,
            'limit' => $limit
        ];
    
       $documents = $this->noteCollection->find(["user_email" => $email->__toString()], $options)->toArray();

       $notes = array_map(function($note) {
            unset($note['_id']);
            return $note;
       }, $documents);

       return $notes;
    }

    /**
     * Update Note
     *
     * @param string $id
     * @param array $data
     * @return void
     */
    public function update(string $id, array $data): void
    {
        $note = $this->noteCollection->find(['id' => $id])->toArray();

        if(empty($note)) {
            throw new NoteNotFound;
        }

        unset($data['email'], $data['id'], $data['user_id']);
        foreach($data as $field => $value) {
            $this->noteCollection->updateOne(["id" => $id],[ '$set' => [$field => $value]]);
        }
    }

    /**
     * Delete Note
     *
     * @param string $id
     * @return void
     */
    public function delete(string $id): void
    {
        $note = $this->noteCollection->find(['id' => $id])->toArray();

        if(empty($note)) {
            throw new NoteNotFound;
        }

        $this->noteCollection->deleteOne(["id" => $id]);
    }
}