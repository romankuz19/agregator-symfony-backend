<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Application\DTO\ValidatedDTO\PasswordRecoveryCompleteDTO;
use App\Application\DTO\ValidatedDTO\PasswordRecoveryDTO;
use App\Domain\Entity\User;
use App\Domain\Repository\ResetPasswordRequestRepository;
use App\Domain\Service\UserService;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

class PasswordRecoveryService
{
    private const PASSWORD_RECOVERY_URL = 'http://127.0.0.1:3000/recovery-complete';
    private const SELECTOR_LENGTH = 20;

    public function __construct(
        private readonly UserService $userService,
        private readonly MailerInterface $mailer,
        private readonly ResetPasswordHelperInterface $resetPasswordHelper,
        private readonly ResetPasswordRequestRepository $resetPasswordRequestRepository,
    ) {
    }

    /**
     * @throws ResetPasswordExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function sendPasswordRecoveryLink(PasswordRecoveryDTO $recoveryDTO): void
    {
        $user = $this->userService->getUserByEmail($recoveryDTO->email);

        $resetToken = $this->resetPasswordHelper->generateResetToken($user);
        $url = self::PASSWORD_RECOVERY_URL . '?token=' . $resetToken->getToken() . '&expires=' . $resetToken->getExpiresAt()->getTimestamp();

        $email = (new TemplatedEmail())
            ->from(new Address('uslugi-agregator@mail.ru', 'Security'))
            ->to($user->getEmail())
            ->subject('Your password reset request')
            ->htmlTemplate('reset_password/email.html.twig')
            ->context([
                'resetToken' => $resetToken,
                'redirectUrl' => $url,
            ])
        ;

        $this->mailer->send($email);
    }

    public function recoveryComplete(PasswordRecoveryCompleteDTO $completeDTO): void
    {
        try {
            $user = $this->resetPasswordHelper->validateTokenAndFetchUser($completeDTO->token);
        } catch (ResetPasswordExceptionInterface $e) {
            throw new \RuntimeException(sprintf('%s - %s', ResetPasswordExceptionInterface::MESSAGE_PROBLEM_VALIDATE, $e->getReason()));
        }

        if (!$user instanceof User) {
            throw new \RuntimeException('User is not a valid reset password.');
        }

        $selector = substr($completeDTO->token, 0, self::SELECTOR_LENGTH);
        $this->resetPasswordRequestRepository->softDeleteByCriteria(['selector' => $selector]);

        $this->userService->updatePassword($user, $completeDTO->password);
    }
}
