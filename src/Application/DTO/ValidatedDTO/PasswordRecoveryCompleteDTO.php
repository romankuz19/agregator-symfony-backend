<?php

declare(strict_types=1);

namespace App\Application\DTO\ValidatedDTO;

use Symfony\Component\Validator\Constraints as Assert;

class PasswordRecoveryCompleteDTO
{
    #[Assert\NotBlank(message: 'Token is required')]
    #[Assert\Type(type: 'string', message: 'Token must be a string')]
    public string $token;

    #[Assert\NotBlank(message: 'Password is required')]
    #[Assert\Type(type: 'string', message: 'Password must be a string')]
    #[Assert\Length(min: 6, max: 255)]
    public string $password;

    #[Assert\NotBlank(message: 'Password is required')]
    #[Assert\Type(type: 'string', message: 'Password must be a string')]
    #[Assert\Length(min: 6, max: 255)]
    #[Assert\EqualTo(propertyPath: 'password', message: 'The passwords do not match.')]
    public string $repeatPassword;
}
