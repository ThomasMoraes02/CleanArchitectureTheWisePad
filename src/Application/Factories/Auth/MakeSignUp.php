<?php 
namespace CleanArchitecture\Application\Factories\Auth;

use DI\Container;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use CleanArchitecture\Application\UseCases\Auth\SignUp;
use CleanArchitecture\Application\Controllers\ControllerOperation;
use CleanArchitecture\Application\Controllers\Auth\SignUpOperation;

class MakeSignUp
{
    private ControllerOperation $controller;

    public function __construct(Container $container)
    {
        $userRepository = $container->get("UserRepository");
        $encoder = $container->get("Encoder");

        $authenticationService = $container->get("AuthenticationService");

        $useCase = new SignUp($userRepository, $encoder, $authenticationService);
        $this->controller = new SignUpOperation($useCase);
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