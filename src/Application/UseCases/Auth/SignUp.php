<?php 
namespace CleanArchitecture\Application\UseCases\Auth;

use InvalidArgumentException;
use CleanArchitecture\Domain\Email;
use CleanArchitecture\Domain\Encoder;
use CleanArchitecture\Domain\User\User;
use CleanArchitecture\Domain\User\UserRepository;
use CleanArchitecture\Application\UseCases\UseCase;
use CleanArchitecture\Application\Exceptions\UserNotFound;

class SignUp implements UseCase
{
    private UserRepository $userRepository;

    private Encoder $encoder;

    private AuthenticationService $authentication;

    public function __construct(UserRepository $userRepository, Encoder $encoder, AuthenticationService $authentication)
    {
        $this->userRepository = $userRepository;
        $this->encoder = $encoder;
        $this->authentication = $authentication;
    }

    public function execute(array $request): array
    {
        try {
            $user = $this->userRepository->findByEmail(new Email($request['email']));

            $userPassword = $this->encoder->decode($request['password'], $user->getEncoder()->__toString());
    
            if(!$userPassword) {
                throw new InvalidArgumentException("Senha inválida");
            }
        } catch(UserNotFound $e) {
            $user = new User($request['name'], new Email($request['email']), new $this->encoder($request['password']));
            $this->userRepository->add($user);
        }

        return $this->authentication->auth($request);
    }
}