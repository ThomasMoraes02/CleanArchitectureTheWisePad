<?php 
namespace CleanArchitecture\Application\UseCases\Note;

use CleanArchitecture\Application\UseCases\UseCase;
use CleanArchitecture\Domain\Email;
use CleanArchitecture\Domain\Note\NoteRepository;
use CleanArchitecture\Domain\User\UserRepository;
use Exception;

class LoadNote implements UseCase
{
    private NoteRepository $noteRepository;

    private UserRepository $userRepository;

    public function __construct(NoteRepository $noteRepository, UserRepository $userRepository)
    {
        $this->noteRepository = $noteRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Load All Notes From User
     *
     * @param array $request
     * @return array
     */
    public function execute(array $request): array
    {
        $userAuth = $this->userRepository->findUserById($request['user_id']);
        $userRequest = $this->userRepository->findByEmail(new Email($request['email']));

        if($userAuth->getEmail() != $userRequest->getEmail()) {
            throw new Exception("Permissão negada a este recurso.");
        }

        if($request['id']) {
            $note = $this->noteRepository->findById($request['id']);
            if($note->getUser()->getEmail() != new Email($request['email'])) {
                throw new Exception("Nota não pertence a este usuário");
            }
            
            return [
                "id" => $request['id'],
                "title" => $note->getTitle()->__toString(),
                "content" => $note->getContent(),
                "user_email" => $request['email']
            ];
        }

        $request['page'] ??= 0;
        $request['limit'] ??= 0;

        return $this->noteRepository->findAllNotesFrom(new Email($request['email']), $request['page'], $request['limit']);
    }
}