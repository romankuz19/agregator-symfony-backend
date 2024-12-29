<?php

declare(strict_types=1);

namespace App\Application\DependencyInjection\Compiler;

use App\Application\ArgumentResolver\DTOResolver;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DTOResolverPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition(DTOResolver::class)) {
            return;
        }

        $dtoResolverDefinition = $container->getDefinition(DTOResolver::class);

        $dtoResolverDefinition->addTag('controller.argument_value_resolver', [
            'priority' => 50,
        ]);
    }
}
