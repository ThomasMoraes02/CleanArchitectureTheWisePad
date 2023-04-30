<?php 
namespace CleanArchitecture\Application\UseCases\Note;

use CleanArchitecture\Application\UseCases\UseCase;
use CleanArchitecture\Domain\Note\NoteRepository;

class DeleteNote implements UseCase
{
    private NoteRepository $noteRepository;

    public function __construct(NoteRepository $noteRepository)
    {
        $this->noteRepository = $noteRepository;
    }

    /**
     * Delete Note
     *
     * @param array $request
     * @return array
     */
    public function execute(array $request): array
    {
        $this->noteRepository->delete($request['id']);

        return [
            "message" => "Nota Deletada"
        ];
    }
}