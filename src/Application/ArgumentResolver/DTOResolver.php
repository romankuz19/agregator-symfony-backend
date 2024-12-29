<?php

declare(strict_types=1);

namespace App\Application\ArgumentResolver;

use App\Application\DTO\ValidatedDTO\CreateUserDTO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DTOResolver implements ValueResolverInterface
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
    ) {
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return is_subclass_of($argument->getType(), CreateUserDTO::class);
    }

    /**
     * @return iterable<CreateUserDTO>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $dto = $this->serializer->deserialize(
            $request->getContent(),
            $argument->getType(),
            'json'
        );

        $errors = $this->validator->validate($dto);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = sprintf(
                    '%s: %s',
                    $error->getPropertyPath(),
                    $error->getMessage()
                );
            }
            throw new BadRequestHttpException(implode(', ', $errorMessages));
        }

        yield $dto;
    }
}
