<?php

declare(strict_types=1);

namespace App\Application\DTO\ValidatedDTO;

use Symfony\Component\Validator\Constraints as Assert;

class RegisterCompleteDTO
{
    #[Assert\NotBlank]
    #[Assert\Type(type: 'integer')]
    public int $userId;

    #[Assert\NotBlank(message: 'Signature is required')]
    #[Assert\Type(type: 'string', message: 'Signature must be a string')]
    public string $signature;

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

    #[Assert\NotBlank(message: 'First name is required')]
    #[Assert\Type(type: 'string', message: 'First name must be a string')]
    #[Assert\Length(max: 255)]
    public string $firstName;

    #[Assert\NotBlank(message: 'Second name is required')]
    #[Assert\Type(type: 'string', message: 'Second name must be a string')]
    #[Assert\Length(max: 255)]
    public string $secondName;

    #[Assert\NotBlank(message: 'City is required')]
    #[Assert\Type(type: 'string', message: 'City must be a string')]
    #[Assert\Length(max: 255)]
    public string $city;

    #[Assert\NotBlank(message: 'Phone number is required')]
    #[Assert\Type(type: 'string', message: 'Phone number must be a string')]
    #[Assert\Length(exactly: 11)]
    public string $phoneNumber;
}
