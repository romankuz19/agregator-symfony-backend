<?php

declare(strict_types=1);

namespace App\Application\DTO\User;

class VerifyEmailDTO
{
    public function __construct(
        private readonly int $userId,
        private readonly string $uri,
    ) {
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getUri(): string
    {
        return $this->uri;
    }
}
