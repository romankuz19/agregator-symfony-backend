<?php

declare(strict_types=1);

namespace App;

use App\Application\DependencyInjection\Compiler\DoctrineExtensionsPass;
use App\Application\DependencyInjection\Compiler\DTOResolverPass;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    protected function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new DTOResolverPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 10);
        //        $container->addCompilerPass(new DoctrineExtensionsPass());
    }
}
