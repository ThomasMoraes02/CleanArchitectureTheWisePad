<?php 
namespace CleanArchitecture\Infraestructure\Repositories;

use CleanArchitecture\Application\Exceptions\NoteNotFound;
use CleanArchitecture\Domain\Email;
use CleanArchitecture\Domain\Note\Note;
use CleanArchitecture\Domain\Note\NoteRepository;
use CleanArchitecture\Domain\Note\Title;
use CleanArchitecture\Domain\User\User;
use CleanArchitecture\Infraestructure\Encoder\EncoderArgonII;


class NoteRepositoryMemory implements NoteRepository
{
    private array $notes = [];

    public function __construct()
    {
        $user = new User("Thomas Moraes", new Email("thomas@gmail.com"), new EncoderArgonII("123456"));
        $note = new Note($user, new Title("Titulo 1"), "conteudo 1");
        $note2 = new Note($user, new Title("Titulo 2"), "conteudo 2");

        $this->add($note);
        $this->add($note2);
    }

    /**
     * Add Note to Repository
     *
     * @param Note $note
     * @return void
     */
    public function add(Note $note): void
    {
        $this->notes[] = $note;
    }

    /**
     * Find Note By Id
     *
     * @param string $id
     * @return Note
     */
    public function findById(string $id): Note
    {
        $note = array_filter($this->notes, fn($note) => $note == $id, ARRAY_FILTER_USE_KEY);

        if(empty($note)) {
            throw new NoteNotFound;
        }

        return current($note);
    }

    /**
     * Find Note By Title
     *
     * @param Title $title
     * @return Note
     */
    public function findyByTitle(Title $title): Note
    {
        $note = array_filter($this->notes, fn($note) => $note->getTitle() == $title);

        if(empty($note)) {
            throw new NoteNotFound;
        }

        return current($note);
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
        $note = array_filter($this->notes, fn($note) => $note == $id, ARRAY_FILTER_USE_KEY);
        $note = reset($note);

        if(empty($note)) {
            throw new NoteNotFound;
        }

        foreach($data as $key => $value) {
            $setKey = "set" . ucfirst($key);
            if(method_exists(Note::class, $setKey)) {
                $note->$setKey($value);
            }
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
        $note = array_filter($this->notes, fn($note) => $note == $id, ARRAY_FILTER_USE_KEY);

        if(empty($note)) {
            throw new NoteNotFound;
        }

        unset($this->notes[$id]);
    }

    /**
     * Find All Notes From User Email
     *
     * @param Email $email
     * @param integer $page
     * @param integer $per_page
     * @return array
     */
    public function findAllNotesFrom(Email $email, int $page = 0, int $per_page = 0): array
    {
        $notes = array_filter($this->notes, fn($note) => $note->getUser()->getEmail()->__toString() == $email);

        return $this->map($notes);
    }

    /**
     * Mapping Note Array
     *
     * @param array $notes
     * @return array
     */
    private function map(array $notes): array
    {
        $allNotes = [];

        /** @var Note $note */
        foreach($notes as $noteKey => $note) {
            $allNotes[] = [
                "id" => $noteKey,
                "title" => $note->getTitle()->__toString(),
                "content" => $note->getContent()
            ];
        }

        return $allNotes;
    }
}