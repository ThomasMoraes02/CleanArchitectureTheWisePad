<?php 
namespace CleanArchitecture\Application\UseCases\Note;

use CleanArchitecture\Application\UseCases\UseCase;
use CleanArchitecture\Domain\Email;
use CleanArchitecture\Domain\Note\NoteRepository;
use Exception;

class LoadNote implements UseCase
{
    private NoteRepository $noteRepository;

    public function __construct(NoteRepository $noteRepository)
    {
        $this->noteRepository = $noteRepository;
    }

    /**
     * Load All Notes From User
     *
     * @param array $request
     * @return array
     */
    public function execute(array $request): array
    {
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