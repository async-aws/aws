<?php

declare(strict_types=1);

namespace AsyncAws\Symfony\Bundle;

use AsyncAws\Symfony\Bundle\DependencyInjection\Compiler\InjectCasterPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AsyncAwsBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new InjectCasterPass());
    }
}
