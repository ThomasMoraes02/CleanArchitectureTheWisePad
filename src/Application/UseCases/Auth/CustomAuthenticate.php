<?php 
namespace CleanArchitecture\Application\UseCases\Auth;

use InvalidArgumentException;
use CleanArchitecture\Domain\Email;
use CleanArchitecture\Domain\Encoder;
use CleanArchitecture\Domain\User\UserRepository;
use CleanArchitecture\Application\UseCases\Auth\TokenManager;

class CustomAuthenticate implements AuthenticationService
{
    private UserRepository $userRepository;

    private Encoder $encoder;

    private TokenManager $tokenManager;

    public function __construct(UserRepository $userRepository, Encoder $encoder, TokenManager $tokenManager)
    {
        $this->userRepository = $userRepository;
        $this->encoder = $encoder;
        $this->tokenManager = $tokenManager;
    }

    /**
     * Auth User
     *
     * @param array $authenticationParams
     * @return array
     */
    public function auth(array $authenticationParams): array
    {
        $user = $this->userRepository->findByEmail(new Email($authenticationParams['email']));
        
        $password = $this->encoder->decode($authenticationParams['password'], $user->getEncoder()->__toString());

        if(!$password) {
            throw new InvalidArgumentException("Senha inválida");
        }

        $id = $this->userRepository->getUserId($user);
        $accessToken = $this->tokenManager->sign(["id" => $id]);

        return [
            "accessToken" => $accessToken
        ];
    }
}