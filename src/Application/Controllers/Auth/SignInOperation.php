<?php 
namespace CleanArchitecture\Application\Controllers\Auth;

use CleanArchitecture\Application\Controllers\ControllerOperation;
use CleanArchitecture\Application\UseCases\UseCase;
use Throwable;

class SignInOperation extends ControllerOperation
{
    private UseCase $useCase;

    public function __construct(UseCase $useCase)
    {
        parent::$requiredFields = ["email", "password"];
        $this->useCase = $useCase;
    }

    /**
     * SigIn Operation
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