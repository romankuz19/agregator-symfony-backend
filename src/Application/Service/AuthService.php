<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Application\DTO\User\AuthenticatedUserDTO;
use App\Application\DTO\ValidatedDTO\LoginDTO;
use App\Domain\Service\UserService;
use Monolog\Attribute\WithMonologChannel;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

#[WithMonologChannel('rmq_logs')]
class AuthService
{
    public function __construct(
        private readonly UserService $userService,
        private readonly JWTService $jwtService,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function login(LoginDTO $loginDTO): AuthenticatedUserDTO
    {
        $this->logger->notice("User with email $loginDTO->email trying to sign in");
        $user = $this->userService->getUserByEmail($loginDTO->email);

        if (!$this->userService->isPasswordValid($user, $loginDTO)) {
            throw new BadCredentialsException('Invalid email or password');
        }

        $token = $this->jwtService->getToken($user);

        return new AuthenticatedUserDTO($user, $token);
    }
}
