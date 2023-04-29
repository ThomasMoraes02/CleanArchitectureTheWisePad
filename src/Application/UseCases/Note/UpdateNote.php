<?php 
namespace CleanArchitecture\Application\UseCases\Note;

use DomainException;
use CleanArchitecture\Domain\Email;
use CleanArchitecture\Domain\Note\Note;
use CleanArchitecture\Domain\Note\NoteRepository;
use CleanArchitecture\Domain\User\UserRepository;
use CleanArchitecture\Application\UseCases\UseCase;

class UpdateNote implements UseCase
{
    private NoteRepository $noteRepository;

    private UserRepository $userRepository;

    public function __construct(NoteRepository $noteRepository, UserRepository $userRepository)
    {
        $this->noteRepository = $noteRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Update Note
     *
     * @param array $request
     * @return array
     */
    public function execute(array $request): array
    {
        $user = $this->userRepository->findByEmail(new Email($request['email']));
        $note = $this->noteRepository->findById($request['id']);

        if($user->getEmail() === $note->getUser()->getEmail()) {
            throw new DomainException("The note is not from this user.");
        }

        if(!$this->verifyTitleAlreadyExists($note)) {
            throw new DomainException("Title already exists: {$request['title']}");
        }

        $this->noteRepository->update($request['id'], $request);

        return [
            "id" => $request['id'],
            "title" => $request['title'],
            "content" => $request['content']
        ];
    }

    /**
     * Verify Title Already Exists
     *
     * @param Note $note
     * @return boolean
     */
    private function verifyTitleAlreadyExists(Note $note): bool
    {
        $userNotes = $this->noteRepository->findAllNotesFrom($note->getUser()->getEmail());

        $noteAlreadyExists = array_filter($userNotes, fn($userNote) => $userNote->getTitle() === $note->getTitle());
        if(!empty($noteAlreadyExists)) {
            return false;
        }

        return true;
    }
}