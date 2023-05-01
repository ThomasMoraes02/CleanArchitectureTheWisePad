<?php 
namespace CleanArchitecture\Application\UseCases\Note;

use Exception;
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
        $userAuth = $this->userRepository->findUserById($request['user_id']);
        $user = $this->userRepository->findByEmail(new Email($request['email']));

        if($userAuth->getEmail() != $user->getEmail()) {
            throw new Exception("Permissão negada a este recurso");
        }

        $note = $this->noteRepository->findById($request['id']);

        if($user->getEmail() != $note->getUser()->getEmail()) {
            throw new DomainException("Essa nota não pertence a este usuário.");
        }

        $noteAlreadyExists = $this->verifyTitleAlreadyExists($note, $request['title']);
        if(!empty($request['title']) && !is_null($noteAlreadyExists)) {
            if($request['id'] != $noteAlreadyExists) {
                throw new DomainException("Titulo já existe: {$request['title']}");
            }
        }

        $this->noteRepository->update($request['id'], $request);

        $note = $this->noteRepository->findById($request['id']);

        return [
            "id" => $request['id'],
            "title" => $note->getTitle()->__toString(),
            "content" => $note->getContent()
        ];
    }

    /**
     * Verify Title Already Exists
     *
     * @param Note $note
     * @param string $title
     * @return ?string
     */
    private function verifyTitleAlreadyExists(Note $note, string $title): ?string
    {
        $userNotes = $this->noteRepository->findAllNotesFrom($note->getUser()->getEmail());

        $noteAlreadyExists = array_filter($userNotes, fn($userNote) => $userNote["title"] == $title);;
        if(!empty($noteAlreadyExists)) {
            return current($noteAlreadyExists)['id'];
        }

        return null;
    }
}