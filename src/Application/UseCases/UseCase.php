<?php 
namespace CleanArchitecture\Application\UseCases;

interface UseCase
{
    /**
     * Execute UseCase 
     *
     * @param array $request
     * @return array
     */
    public function execute(array $request): array;
}