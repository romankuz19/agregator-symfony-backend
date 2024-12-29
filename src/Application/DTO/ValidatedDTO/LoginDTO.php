<?php

declare(strict_types=1);

namespace App\Application\DTO\ValidatedDTO;

use Symfony\Component\Validator\Constraints as Assert;

class LoginDTO
{
    #[Assert\NotBlank(message: 'Email is required')]
    #[Assert\Type(type: 'string', message: 'Username must be a string')]
    #[Assert\Email]
    public string $email;

    #[Assert\NotBlank(message: 'Password is required')]
    #[Assert\Type(type: 'string', message: 'Password must be a string')]
    #[Assert\Length(min: 6, max: 255)]
    public string $password;
}
