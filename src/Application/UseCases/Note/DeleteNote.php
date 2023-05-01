<?php 
namespace CleanArchitecture\Application\UseCases\Note;

use CleanArchitecture\Application\UseCases\UseCase;
use CleanArchitecture\Domain\Note\NoteRepository;
use CleanArchitecture\Domain\User\UserRepository;
use Exception;

class DeleteNote implements UseCase
{
    private NoteRepository $noteRepository;

    private UserRepository $userRepository;

    public function __construct(NoteRepository $noteRepository, UserRepository $userRepository)
    {
        $this->noteRepository = $noteRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Delete Note
     *
     * @param array $request
     * @return array
     */
    public function execute(array $request): array
    {
        $note = $this->noteRepository->findById($request['id']);

        $user = $this->userRepository->findUserById($request['user_id']);

        if($note->getUser()->getEmail() != $user->getEmail()) {
            throw new Exception("Você não tem permissão para excluir esta nota");
        }

        $this->noteRepository->delete($request['id']);

        return [
            "message" => "Nota Deletada"
        ];
    }
}