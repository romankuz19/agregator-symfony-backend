<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Application\DTO\User\AuthenticatedUserDTO;
use App\Application\DTO\ValidatedDTO\LoginDTO;
use App\Domain\Service\UserService;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class AuthService
{
    public function __construct(
        private readonly UserService $userService,
        private readonly JWTService $jwtService,
    ) {
    }

    public function login(LoginDTO $loginDTO): AuthenticatedUserDTO
    {
        $user = $this->userService->getUserByEmail($loginDTO->email);

        if (!$this->userService->isPasswordValid($user, $loginDTO)) {
            throw new BadCredentialsException('Invalid username or password');
        }

        $token = $this->jwtService->getToken($user);

        return new AuthenticatedUserDTO($user, $token);
    }
}
