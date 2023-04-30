<?php 
namespace CleanArchitecture\Application\Factories\Note;

use DI\Container;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use CleanArchitecture\Application\UseCases\Note\LoadNote;
use CleanArchitecture\Application\Controllers\ControllerOperation;
use CleanArchitecture\Application\Controllers\Note\LoadNoteOperation;

class MakeLoadNote
{
    private ControllerOperation $controller;

    public function __construct(Container $container)
    {
        $noteRepository = $container->get("NoteRepository");
        $userRepository = $container->get("UserRepository");

        $useCase = new LoadNote($noteRepository);
        $this->controller = new LoadNoteOperation($useCase);
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $payload = json_decode($request->getBody(), true);
        $responseController = $this->controller->handle($payload);

        $response->getBody()->write(json_encode($responseController));

        return $response->withHeader('Content-Type', 'application/json')
        ->withHeader("Cache-Control", "no-cache")
        ->withHeader("Cache-Control", "max-age=0")
        ->withHeader("Cache-Control", "must-revalidate")
        ->withHeader("Cache-Control", "proxy-revalidate")
        ->withStatus($responseController['statusCode']);
    }
}