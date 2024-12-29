<?php

declare(strict_types=1);

namespace App\Application\Service;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class JWTService
{
    public function __construct(private readonly JWTTokenManagerInterface $tokenManager)
    {
    }

    public function getToken(UserInterface $user): string
    {
        return $this->tokenManager->create($user);
    }
}
