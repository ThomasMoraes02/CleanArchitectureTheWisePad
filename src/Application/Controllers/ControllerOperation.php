<?php 
namespace CleanArchitecture\Application\Controllers;

use CleanArchitecture\Application\Helpers\HttpStatusHelper;
use InvalidArgumentException;
use CleanArchitecture\Application\UseCases\UseCase;

abstract class ControllerOperation
{
    use HttpStatusHelper;

    /** @var array $requiredFields */
    protected static array $requiredFields;

    abstract public function __construct(UseCase $useCase);

    abstract public function handle(array $request): array;

    /**
     * Verify Required Fields
     *
     * @param array $request
     * @return void
     */
    public static function getMissingParams(array $request): void
    {
        $missingParams = [];

        for ($i=0; $i < count(static::$requiredFields); $i++) { 
            if(!in_array(static::$requiredFields[$i], array_keys($request)) || empty($request[static::$requiredFields[$i]])) {
                $missingParams[] = static::$requiredFields[$i];
            }
        }
    
        if(!empty($missingParams)) {
            throw new InvalidArgumentException("Campos obrigatórios: " . implode(", ", $missingParams));
        }
    }
}