<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Application\Assembler\RegisterCompleteUpdateUserDTOAssembler;
use App\Application\DTO\User\AuthenticatedUserDTO;
use App\Application\DTO\ValidatedDTO\CreateUserDTO;
use App\Application\DTO\ValidatedDTO\RegisterCompleteDTO;
use App\Application\Exception\UserAlreadyExistsException;
use App\Application\Security\Register\EmailVerifier;
use App\Domain\Service\UserService;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

class RegisterService
{
    public function __construct(
        private readonly UserService $userService,
        private readonly JWTService $jwtService,
        private readonly RegisterCompleteUpdateUserDTOAssembler $registerCompleteUpdateUserDTOAssembler,
        private readonly EmailVerifier $emailVerifier,
    ) {
    }

    public function register(CreateUserDTO $userDTO): void
    {
        try {
            $user = $this->userService->getUserByEmail($userDTO->email);

            if ($user->isVerified()) {
                throw new UserAlreadyExistsException("User with email $userDTO->email already exists.");
            }
        } catch (UserNotFoundException) {
            $user = $this->userService->createUnverifiedUser($userDTO);
        }

        $this->emailVerifier->sendEmailConfirmation('api_auth_register_complete', $user,
            (new TemplatedEmail())
                ->from(new Address('mailer@example.com', 'Security'))
                ->to($user->getEmail())
                ->subject('Please Confirm your Email')
                ->htmlTemplate('registration/confirmation_email.html.twig')
        );
    }

    public function registerComplete(RegisterCompleteDTO $registerCompleteDTO): AuthenticatedUserDTO
    {
        $user = $this->userService->getUserById($registerCompleteDTO->userId);

        $this->emailVerifier->handleEmailConfirmation($registerCompleteDTO, $user);

        $updateUserDTO = $this->registerCompleteUpdateUserDTOAssembler->assemble($registerCompleteDTO);

        $this->userService->updateUser($updateUserDTO);

        $token = $this->jwtService->getToken($user);

        return new AuthenticatedUserDTO($user, $token);
    }
}
