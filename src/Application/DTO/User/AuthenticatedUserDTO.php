<?php

declare(strict_types=1);

namespace App\Application\DTO\User;

use App\Domain\Entity\User;

class AuthenticatedUserDTO
{
    public function __construct(
        private readonly User $user,
        private readonly string $token,
    ) {
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getToken(): string
    {
        return $this->token;
    }
}
