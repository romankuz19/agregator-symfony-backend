<?php

declare(strict_types=1);

namespace App\Application\DTO\ValidatedDTO;

use Symfony\Component\Validator\Constraints as Assert;

class PasswordRecoveryDTO
{
    #[Assert\NotBlank(message: 'Email is required')]
    #[Assert\Type(type: 'string', message: 'Username must be a string')]
    #[Assert\Email]
    public string $email;
}
