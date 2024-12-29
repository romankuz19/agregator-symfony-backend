<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Application\DTO\User\UpdateUserDTO;
use App\Application\DTO\ValidatedDTO\CreateUserDTO;
use App\Application\DTO\ValidatedDTO\LoginDTO;
use App\Domain\Entity\User;
use App\Domain\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

class UserService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function createUnverifiedUser(CreateUserDTO $userDTO): User
    {
        $user = new User();
        $user->setEmail($userDTO->email)->setIsVerified(false);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function getUserByEmail(string $email): User
    {
        $user = $this->userRepository->findOneBy(['email' => $email]);

        if (!$user) {
            throw new UserNotFoundException('User not found');
        }

        return $user;
    }

    public function getUserById(int $id): User
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            throw new UserNotFoundException('User not found');
        }

        return $user;
    }

    public function isPasswordValid(User $user, LoginDTO $loginDTO): bool
    {
        return $this->passwordHasher->isPasswordValid($user, $loginDTO->password);
    }

    public function updateUser(UpdateUserDTO $userDTO): User
    {
        $user = $this->userRepository->find($userDTO->getUserId());

        if (!$user) {
            throw new UserNotFoundException('User not found');
        }

        if (null !== $userDTO->getEmail()) {
            $user->setEmail($userDTO->getEmail());
        }
        if (null !== $userDTO->getPassword()) {
            $user->setPassword($this->passwordHasher->hashPassword($user, $userDTO->getPassword()));
        }
        if (null !== $userDTO->getPhoneNumber()) {
            $user->setPhoneNumber($userDTO->getPhoneNumber());
        }
        if (null !== $userDTO->getFirstName()) {
            $user->setFirstName($userDTO->getFirstName());
        }
        if (null !== $userDTO->getSecondName()) {
            $user->setSecondName($userDTO->getSecondName());
        }
        if (null !== $userDTO->getCity()) {
            $user->setCity($userDTO->getCity());
        }

        $this->entityManager->flush();

        return $user;
    }

    public function verifyUser(User $user): void
    {
        $user->setIsVerified(true);
        $this->entityManager->flush();
    }

    public function updatePassword(User $user, string $plainPassword): void
    {
        $encodedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);

        $user->setPassword($encodedPassword);
        $this->entityManager->flush();
    }
}
