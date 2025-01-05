<?php

declare(strict_types=1);

namespace App\Application\Security\Register;

use App\Application\DTO\ValidatedDTO\RegisterCompleteDTO;
use App\Domain\Entity\User;
use App\Domain\Service\UserService;
use App\Infrastructure\Service\Cache\RedisService;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\UriSigner;
use Symfony\Component\Mailer\MailerInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\ExpiredSignatureException;
use SymfonyCasts\Bundle\VerifyEmail\Exception\InvalidSignatureException;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\WrongEmailVerifyException;
use SymfonyCasts\Bundle\VerifyEmail\Generator\VerifyEmailTokenGenerator;
use SymfonyCasts\Bundle\VerifyEmail\Model\VerifyEmailSignatureComponents;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class EmailVerifier
{
    private const CACHE_KEY = 'register_attempt::';
    private const TTL = 3600;
    private const REGISTER_COMPLETE_URL = 'http://127.0.0.1:3000/register-complete';

    public function __construct(
        private readonly VerifyEmailHelperInterface $verifyEmailHelper,
        private readonly MailerInterface $mailer,
        private readonly RedisService $cache,
        private readonly UriSigner $uriSigner,
        private readonly VerifyEmailTokenGenerator $tokenGenerator,
        private readonly UserService $userService,
    ) {
    }

    public function sendEmailConfirmation(string $verifyEmailRouteName, User $user, TemplatedEmail $email): void
    {
        if ($this->cache->has($this->getCacheKey($user->getId()))) {
            return;
        }

        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            $verifyEmailRouteName,
            (string) $user->getId(),
            $user->getEmail(),
            ['id' => $user->getId()]
        );

        $context = $email->getContext();
        $context['signedUrl'] = $this->getSignedUrl($signatureComponents);
        $context['expiresAtMessageKey'] = $signatureComponents->getExpirationMessageKey();
        $context['expiresAtMessageData'] = $signatureComponents->getExpirationMessageData();

        $email->context($context);

        $this->mailer->send($email);

        $this->cache->set(
            $this->getCacheKey($user->getId()),
            json_encode(['user_id' => $user->getId(), 'email' => $user->getEmail(), 'signedUrl' => $signatureComponents->getSignedUrl()]),
            self::TTL
        );
    }

    /**
     * @throws VerifyEmailExceptionInterface
     */
    public function handleEmailConfirmation(RegisterCompleteDTO $registerCompleteDTO, User $user): void
    {
        $uri = $this->getCachedData($user->getId())['signedUrl'] ?? '';

        if (!$this->uriSigner->check($uri)) {
            throw new InvalidSignatureException('Invalid signature');
        }

        $knownToken = $this->tokenGenerator->createToken((string) $user->getId(), $user->getEmail());

        if (!hash_equals($knownToken, $registerCompleteDTO->token)) {
            throw new WrongEmailVerifyException('Invalid token');
        }

        $this->userService->verifyUser($user);

        $this->cache->delete($this->getCacheKey($user->getId()));
    }

    private function getSignedUrl(VerifyEmailSignatureComponents $signatureComponents): string
    {
        $parsedUrl = parse_url($signatureComponents->getSignedUrl());
        $queryParams = $parsedUrl['query'];

        return self::REGISTER_COMPLETE_URL . '?' . $queryParams;
    }

    private function getCacheKey(int $userId): string
    {
        return self::CACHE_KEY . $userId;
    }

    /**
     * @return array<string, mixed>
     *
     * @throws ExpiredSignatureException
     */
    private function getCachedData(int $userId): array
    {
        $cachedData = $this->cache->get($this->getCacheKey($userId));

        if (null === $cachedData) {
            throw new ExpiredSignatureException('Signature is expired.');
        }

        return json_decode($cachedData, true);
    }
}
