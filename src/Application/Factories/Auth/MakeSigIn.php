<?php 
namespace CleanArchitecture\Application\Factories\Auth;

use DI\Container;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use CleanArchitecture\Application\UseCases\Auth\SignIn;
use CleanArchitecture\Application\Controllers\ControllerOperation;
use CleanArchitecture\Application\Controllers\Auth\SignInOperation;

class MakeSigIn
{
    private ControllerOperation $controller;

    public function __construct(Container $container)
    {
        $authenticationService = $container->get("AuthenticationService");
        $useCase = new SignIn($authenticationService);
        $this->controller = new SignInOperation($useCase);
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