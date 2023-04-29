<?php

use CleanArchitecture\Application\UseCases\Auth\CustomAuthenticate;
use CleanArchitecture\Infraestructure\Authentication\TokenJWT;
use CleanArchitecture\Infraestructure\Encoder\EncoderArgonII;
use CleanArchitecture\Infraestructure\Repositories\NoteRepositoryMemory;
use CleanArchitecture\Infraestructure\Repositories\UserRepositoryMemory;
use DI\ContainerBuilder;

use function DI\create;
use function DI\get;

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions([
    "UserRepository" => create(UserRepositoryMemory::class),
    "NoteRepository" => create(NoteRepositoryMemory::class),
    "Encoder" => create(EncoderArgonII::class),
    "TokenManager" => create(TokenJWT::class),
    "AuthenticationService" => create(CustomAuthenticate::class)->constructor(get("UserRepository"), get("Encoder"), get("TokenManager"))
]);
return $containerBuilder->build();