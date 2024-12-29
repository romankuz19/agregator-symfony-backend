<?php

declare(strict_types=1);

namespace App\Application\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DoctrineExtensionsPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $eventManager = $container->getDefinition('doctrine.orm.default_entity_manager');
        $eventManager->addMethodCall('getEventManager');

        $listener = $container->getDefinition('Gedmo\SoftDeleteable\SoftDeleteableListener');
        $eventManager->addMethodCall('addEventListener', [
            ['onFlush'],
            $listener,
        ]);
    }
}
