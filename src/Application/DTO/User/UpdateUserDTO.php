<?php

declare(strict_types=1);

namespace App\Application\DTO\User;

class UpdateUserDTO
{
    public function __construct(
        private readonly int $userId,
        private readonly ?string $email,
        private readonly ?string $password,
        private readonly ?string $firstName,
        private readonly ?string $secondName,
        private readonly ?string $city,
        private readonly ?int $phoneNumber,
    ) {
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getSecondName(): ?string
    {
        return $this->secondName;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function getPhoneNumber(): ?int
    {
        return $this->phoneNumber;
    }
}
