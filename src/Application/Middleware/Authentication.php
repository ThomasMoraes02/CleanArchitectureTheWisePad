<?php 
namespace CleanArchitecture\Application\Middleware;

use DateTime;
use Exception;
use CleanArchitecture\Application\Helpers\HttpStatusHelper;
use CleanArchitecture\Application\UseCases\Auth\TokenManager;
use CleanArchitecture\Domain\User\UserRepository;

class Authentication implements Middleware
{
    use HttpStatusHelper;

    private TokenManager $tokenManager;

    private UserRepository $userRepository;

    public function __construct(TokenManager $tokenManager, UserRepository $userRepository)
    {
        $this->tokenManager = $tokenManager;
        $this->userRepository = $userRepository;
    }

    /**
     * Verify Access Token
     * Return User Id Access Token
     *
     * @param array|object|string $requestMiddleware
     * @return string
     * @throws Exception
     */
    public function authenticate($requestMiddleware): string
    {
        if(empty($requestMiddleware)) {
            throw new Exception("Token de Autenticação precisa ser enviado.");
        }

        $accessToken = str_replace("Bearer ", "", $requestMiddleware);
        $decode = $this->tokenManager->verify($accessToken);

        $expires = new DateTime(date("Y-m-d", $decode->exp));
        $interval = $expires->diff(new DateTime());

        if($interval->days > 1) {
            throw new Exception("Token Expirado");
        }

        if(empty($decode->payload->id)) {
            throw new Exception("Token não identificado");
        }

        $user = $this->userRepository->findUserById($decode->payload->id);

        if(is_null($user)) {
            throw new Exception("Usuário inválido");
        }

        return $decode->payload->id;
    }
}