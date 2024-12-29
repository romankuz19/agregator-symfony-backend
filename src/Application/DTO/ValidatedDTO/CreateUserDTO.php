<?php

declare(strict_types=1);

namespace App\Application\DTO\ValidatedDTO;

use Symfony\Component\Validator\Constraints as Assert;

class CreateUserDTO
{
    #[Assert\NotBlank(message: 'Email is required')]
    #[Assert\Email(message: 'Email is not valid')]
    public string $email;
}
