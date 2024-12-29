<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Application\Exception\UserAlreadyExistsException;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use SymfonyCasts\Bundle\ResetPassword\Exception\TooManyPasswordRequestsException;

#[AsEventListener(priority: 256)]
class ExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $response = new JsonResponse();

        switch (true) {
            case $exception instanceof UserNotFoundException:
                $response->setStatusCode(Response::HTTP_UNAUTHORIZED);
                $response->setData([
                    'code' => Response::HTTP_UNAUTHORIZED,
                    'message' => $exception->getMessage(),
                ]);
                break;
            case $exception instanceof UserAlreadyExistsException:
                $response->setStatusCode(Response::HTTP_CONFLICT);
                $response->setData([
                    'code' => Response::HTTP_CONFLICT,
                    'message' => $exception->getMessage(),
                ]);
                break;
            case $exception instanceof TooManyPasswordRequestsException:
                $response->setStatusCode(Response::HTTP_CONFLICT);
                $response->setData([
                    'code' => Response::HTTP_CONFLICT,
                    'message' => 'We have already sent a password request. Please check your email.',
                ]);
                break;
            default:
                $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
                $response->setData([
                    'code' => $exception instanceof HttpExceptionInterface ? $exception->getStatusCode() : 500,
                    'message' => $exception->getMessage(),
                ]);
                break;
        }

        $event->setResponse($response);
    }
}
