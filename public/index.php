<?php

use CleanArchitecture\Application\Factories\Auth\{MakeSigIn, MakeSignUp};
use CleanArchitecture\Application\Factories\Middleware\MakeAuthenticateMiddleware;
use CleanArchitecture\Application\Factories\Note\{MakeCreateNote, MakeDeleteNote, MakeLoadNote, MakeUpdateNote};
use Slim\Psr7\{Request, Response};
use Slim\Routing\RouteCollectorProxy;

require_once __DIR__ . "/../config/bootstrap.php";

$app->get("/", function(Request $request, Response $response, array $args) {
    $response->getBody()->write(json_encode("Hello World"));

    return $response->withHeader('Content-Type', 'application/json')
    ->withHeader("Cache-Control", "no-cache")
    ->withHeader("Cache-Control", "max-age=0")
    ->withHeader("Cache-Control", "must-revalidate")
    ->withHeader("Cache-Control", "proxy-revalidate")
    ->withStatus(200);
});

$app->post("/sigin", MakeSigIn::class);
$app->post("/signup", MakeSignUp::class);

$app->group("/notes", function(RouteCollectorProxy $group) {
    $group->post("", MakeCreateNote::class);
    $group->get("", MakeLoadNote::class);
    $group->put("/{id}", MakeUpdateNote::class);
    $group->delete("/{id}", MakeDeleteNote::class);
})->add(MakeAuthenticateMiddleware::class);

$app->run();