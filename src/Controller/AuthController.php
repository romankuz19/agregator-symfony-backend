<?php

declare(strict_types=1);

namespace App\Controller;

use App\Application\DTO\ValidatedDTO\CreateUserDTO;
use App\Application\DTO\ValidatedDTO\LoginDTO;
use App\Application\DTO\ValidatedDTO\PasswordRecoveryCompleteDTO;
use App\Application\DTO\ValidatedDTO\PasswordRecoveryDTO;
use App\Application\DTO\ValidatedDTO\RegisterCompleteDTO;
use App\Application\Service\AuthService;
use App\Application\Service\JWTService;
use App\Application\Service\PasswordRecoveryService;
use App\Application\Service\RegisterService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/auth', name: 'api_auth_')]
class AuthController extends AbstractController
{
    public function __construct(
        private readonly JWTService $JWTService,
        private readonly AuthService $authService,
        private readonly RegisterService $registerService,
        private readonly PasswordRecoveryService $passwordRecoveryService,
    ) {
    }

    #[Route('/register', name: 'register', methods: ['POST'])]
    public function register(CreateUserDTO $userDTO): JsonResponse
    {
        $this->registerService->register($userDTO);

        return $this->json([
            'message' => 'To continue registration, please check your email.',
        ]);
    }

    #[Route('/register-complete', name: 'register_complete', methods: ['POST'])]
    public function registerComplete(RegisterCompleteDTO $registerCompleteDTO): JsonResponse
    {
        $authDTO = $this->registerService->registerComplete($registerCompleteDTO);

        return $this->json(
            [
                'user' => $authDTO->getUser(),
                'token' => $authDTO->getToken(),
                'message' => 'Registration successfully completed.',
            ],
            Response::HTTP_CREATED
        );
    }

    #[Route('/login', name: 'login', methods: ['POST'])]
    public function login(LoginDTO $loginDTO): JsonResponse
    {
        $authDTO = $this->authService->login($loginDTO);

        return $this->json([
            'user' => $authDTO->getUser(),
            'token' => $authDTO->getToken(),
        ]);
    }

    #[Route('/me', name: 'get_me', methods: ['GET'])]
    public function getMe(): JsonResponse
    {
        $user = $this->getUser();

        $token = $this->JWTService->getToken($user);

        return $this->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    #[Route('/send-recovery-link', name: 'send_recovery_link', methods: ['POST'])]
    public function sendPasswordRecoveryLink(PasswordRecoveryDTO $recoveryDTO): JsonResponse
    {
        $this->passwordRecoveryService->sendPasswordRecoveryLink($recoveryDTO);

        return $this->json([
            'message' => 'To continue password recovery, please check your email.',
        ]);
    }

    #[Route('/password-recovery-complete', name: 'password_recovery_complete', methods: ['POST'])]
    public function passwordRecoveryComplete(PasswordRecoveryCompleteDTO $recoveryDTO): JsonResponse
    {
        $this->passwordRecoveryService->recoveryComplete($recoveryDTO);

        return $this->json([
            'message' => 'Password successfully changed.',
        ]);
    }
}
