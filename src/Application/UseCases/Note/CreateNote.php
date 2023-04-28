<?php 
namespace CleanArchitecture\Application\UseCases\Note;

use CleanArchitecture\Application\UseCases\UseCase;
use CleanArchitecture\Domain\Email;
use CleanArchitecture\Domain\Note\Note;
use CleanArchitecture\Domain\Note\NoteRepository;
use CleanArchitecture\Domain\Note\Title;
use CleanArchitecture\Entities\User\UserRepository;
use DomainException;

class CreateNote implements UseCase
{
    private NoteRepository $noteRepository;

    private UserRepository $userRepository;

    public function __construct(NoteRepository $noteRepository, UserRepository $userRepository)
    {
        $this->noteRepository = $noteRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Create Note
     *
     * @param array $request
     * @return array
     */
    public function execute(array $request): array
    {
        $user = $this->userRepository->findByEmail(new Email($request['email']));

        $note = new Note($user, new Title($request['title']), $request['content']);

        $userNotes = $this->noteRepository->findAllNotesFrom(new Email($request['email']));

        $this->findNoteByTitle($note->getTitle(), $userNotes);

        $this->noteRepository->add($note);

        return [
            "title" => $note->getTitle(),
            "content" => $note->getContent()
        ];
    }

    /**
     * Verify All Notes From User
     *
     * @param string $title
     * @param array $userNotes
     * @return void
     * @throws DomainException
     */
    private function findNoteByTitle(string $title, array $userNotes): void
    {
        if(!empty($userNotes)) {
            $note = array_filter($userNotes, fn($userNote) => $userNote->getTitle() == $title);
            if(!empty($note)) {
                throw new DomainException("Note already exists: {$title}");
            }
        }
    }
}