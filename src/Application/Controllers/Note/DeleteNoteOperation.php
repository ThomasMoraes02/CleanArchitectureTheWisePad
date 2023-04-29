<?php
namespace CleanArchitecture\Application\Controllers\Note;

use CleanArchitecture\Application\Controllers\ControllerOperation;
use CleanArchitecture\Application\UseCases\UseCase;
use Throwable;

class DeleteNoteOperation extends ControllerOperation
{
    private UseCase $useCase;

    public function __construct(UseCase $useCase)
    {
        parent::$requiredFields = ["id"];
        $this->useCase = $useCase;
    }

    /**
     * Delete Note Operation
     *
     * @param array $request
     * @return array
     */
    public function handle(array $request): array
    {
        try {
            ControllerOperation::getMissingParams($request);

            $response = $this->useCase->execute($request);
            return $this->ok($response);
        } catch(Throwable $e) {
            return $this->badRequest($e->getMessage());
        }
        return $this->serverError();
    }
}