<?php

declare(strict_types=1);

namespace App\Application\Assembler;

use App\Application\DTO\User\VerifyEmailDTO;
use Symfony\Component\HttpFoundation\Request;

class VerifyEmailRequestDTOAssembler
{
    public function assemble(Request $request): VerifyEmailDTO
    {
        return new VerifyEmailDTO(
            $request->query->get('userId'),
            $request->query->get('uri'),
        );
    }
}
