<?php 
namespace CleanArchitecture\Application\UseCases\Note;

use CleanArchitecture\Application\UseCases\UseCase;
use CleanArchitecture\Domain\Email;
use CleanArchitecture\Domain\Note\NoteRepository;

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
        return $this->noteRepository->findAllNotesFrom(new Email($request['email']));
    }
}