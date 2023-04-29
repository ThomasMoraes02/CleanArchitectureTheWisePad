<?php
namespace CleanArchitecture\Application\Controllers\Note;

use CleanArchitecture\Application\Controllers\ControllerOperation;
use CleanArchitecture\Application\UseCases\UseCase;
use Throwable;

class CreateNoteOperation extends ControllerOperation
{
    private UseCase $useCase;

    public function __construct(UseCase $useCase)
    {
        parent::$requiredFields = ["title", "content"];
        $this->useCase = $useCase;
    }

    /**
     * Create Note Operation
     *
     * @param array $request
     * @return array
     */
    public function handle(array $request): array
    {
        try {
            ControllerOperation::getMissingParams($request);

            $response = $this->useCase->execute($request);
            return $this->created($response);
        } catch(Throwable $e) {
            return $this->badRequest($e->getMessage());
        }
        return $this->serverError();
    }
}