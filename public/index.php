<?php

use Slim\Psr7\Request;
use Slim\Psr7\Response;

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

$app->run();