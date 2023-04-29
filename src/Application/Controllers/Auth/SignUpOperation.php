<?php 
namespace CleanArchitecture\Application\Controllers\Auth;

use CleanArchitecture\Application\Controllers\ControllerOperation;
use CleanArchitecture\Application\UseCases\UseCase;
use Throwable;

class SignUpOperation extends ControllerOperation
{
    private UseCase $useCase;

    public function __construct(UseCase $useCase)
    {
        parent::$requiredFields = ["name", "email", "password"];
        $this->useCase = $useCase;
    }

    /**
     * SignUp Operation
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