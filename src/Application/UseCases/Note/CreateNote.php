<?php 
namespace CleanArchitecture\Application\UseCases\Note;

use DomainException;
use CleanArchitecture\Domain\Email;
use CleanArchitecture\Domain\Note\Note;
use CleanArchitecture\Domain\Note\Title;
use CleanArchitecture\Domain\Note\NoteRepository;
use CleanArchitecture\Domain\User\UserRepository;
use CleanArchitecture\Application\UseCases\UseCase;
use Exception;

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
        $userAuth = $this->userRepository->findUserById($request['user_id']);
        $user = $this->userRepository->findByEmail(new Email($request['email']));

        if($userAuth->getEmail() != $user->getEmail()) {
            throw new Exception("Permissão negada a este recurso");
        }

        $note = new Note($user, new Title($request['title']), $request['content']);

        $userNotes = $this->noteRepository->findAllNotesFrom(new Email($request['email']));
        if(!empty($userNotes)) {
            $this->findNoteByTitle($note->getTitle(), $userNotes);
        }

        $this->noteRepository->add($note);

        return [
            "title" => $note->getTitle()->__toString(),
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
            $note = array_filter($userNotes, fn($userNote) => $userNote['title'] == $title);
            if(!empty($note)) {
                throw new DomainException("O titulo enviado já existe: {$title}");
            }
        }
    }
}