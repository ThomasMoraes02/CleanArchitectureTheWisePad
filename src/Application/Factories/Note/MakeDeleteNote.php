<?php 
namespace CleanArchitecture\Application\Factories\Note;

use DI\Container;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use CleanArchitecture\Application\UseCases\Note\DeleteNote;
use CleanArchitecture\Application\Controllers\ControllerOperation;
use CleanArchitecture\Application\Controllers\Note\DeleteNoteOperation;

class MakeDeleteNote
{
    private ControllerOperation $controller;

    public function __construct(Container $container)
    {
        $noteRepository = $container->get("NoteRepository");
        $userRepository = $container->get("UserRepository");

        $useCase = new DeleteNote($noteRepository, $userRepository);
        $this->controller = new DeleteNoteOperation($useCase);
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $payload["id"] = $args["id"];
        $payload['user_id'] = $request->getAttribute("user_id");
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