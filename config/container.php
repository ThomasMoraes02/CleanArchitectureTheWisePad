<?php

use function DI\get;
use function DI\create;
use DI\ContainerBuilder;
use CleanArchitecture\Application\Middleware\Authentication;
use CleanArchitecture\Infraestructure\Encoder\EncoderArgonII;
use CleanArchitecture\Infraestructure\Authentication\TokenJWT;
use CleanArchitecture\Application\UseCases\Auth\CustomAuthenticate;
use CleanArchitecture\Infraestructure\Repositories\Mongo\MongoDBConnection;
use CleanArchitecture\Infraestructure\Repositories\Mongo\NoteRepositoryMongodb;
use CleanArchitecture\Infraestructure\Repositories\Mongo\UserRepositoryMongodb;

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions([
    "UserRepository" => create(UserRepositoryMongodb::class)->constructor(get("Encoder"), new MongoDBConnection),
    "NoteRepository" => create(NoteRepositoryMongodb::class)->constructor(get("UserRepository"), new MongoDBConnection),
    "Encoder" => create(EncoderArgonII::class),
    "TokenManager" => create(TokenJWT::class),
    "AuthenticationService" => create(CustomAuthenticate::class)->constructor(get("UserRepository"), get("Encoder"), get("TokenManager")),
    "Middleware" => create(Authentication::class)->constructor(get("TokenManager"), get("UserRepository"))
]);
return $containerBuilder->build();