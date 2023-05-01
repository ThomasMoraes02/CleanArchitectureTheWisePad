<?php

use CleanArchitecture\Application\Middleware\Authentication;
use CleanArchitecture\Application\UseCases\Auth\CustomAuthenticate;
use CleanArchitecture\Infraestructure\Authentication\TokenJWT;
use CleanArchitecture\Infraestructure\Encoder\EncoderArgonII;
use CleanArchitecture\Infraestructure\Repositories\NoteRepositoryMongodb;
use CleanArchitecture\Infraestructure\Repositories\UserRepositoryMongodb;
use DI\ContainerBuilder;

use function DI\create;
use function DI\get;

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions([
    "UserRepository" => create(UserRepositoryMongodb::class)->constructor(get("Encoder")),
    "NoteRepository" => create(NoteRepositoryMongodb::class)->constructor(get("UserRepository")),
    "Encoder" => create(EncoderArgonII::class),
    "TokenManager" => create(TokenJWT::class),
    "AuthenticationService" => create(CustomAuthenticate::class)->constructor(get("UserRepository"), get("Encoder"), get("TokenManager")),
    "Middleware" => create(Authentication::class)->constructor(get("TokenManager"), get("UserRepository"))
]);
return $containerBuilder->build();