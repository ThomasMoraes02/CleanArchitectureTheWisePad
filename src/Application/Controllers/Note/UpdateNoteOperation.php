<?php
namespace CleanArchitecture\Application\Controllers\Note;

use CleanArchitecture\Application\Controllers\ControllerOperation;
use CleanArchitecture\Application\UseCases\UseCase;
use Throwable;

class UpdateNoteOperation extends ControllerOperation
{
    private UseCase $useCase;

    public function __construct(UseCase $useCase)
    {
        $this->useCase = $useCase;
    }

    /**
     * Update Note Operation
     *
     * @param array $request
     * @return array
     */
    public function handle(array $request): array
    {
        try {
            $response = $this->useCase->execute($request);
            return $this->ok($response);
        } catch(Throwable $e) {
            return $this->badRequest($e->getMessage());
        }
        return $this->serverError();
    }
}