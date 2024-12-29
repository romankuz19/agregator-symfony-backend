<?php

declare(strict_types=1);

namespace App\Application\Assembler;

use App\Application\DTO\User\UpdateUserDTO;
use App\Application\DTO\ValidatedDTO\RegisterCompleteDTO;

class RegisterCompleteUpdateUserDTOAssembler
{
    public function assemble(RegisterCompleteDTO $registerCompleteDTO): UpdateUserDTO
    {
        return new UpdateUserDTO(
            userId: $registerCompleteDTO->userId,
            email: null,
            password: $registerCompleteDTO->password,
            firstName: $registerCompleteDTO->firstName,
            secondName: $registerCompleteDTO->secondName,
            city: $registerCompleteDTO->city,
            phoneNumber: $registerCompleteDTO->phoneNumber,
        );
    }
}
