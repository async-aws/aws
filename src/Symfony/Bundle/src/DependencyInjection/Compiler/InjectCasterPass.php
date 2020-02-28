<?php

namespace AsyncAws\Symfony\Bundle\DependencyInjection\Compiler;

use AsyncAws\Core\Result;
use AsyncAws\Core\Waiter;
use AsyncAws\Symfony\Bundle\VarDumper\ResultCaster;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class InjectCasterPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('var_dumper.cloner')) {
            return;
        }

        $container->getDefinition('var_dumper.cloner')
            ->addMethodCall('addCasters', [[
                Result::class => [ResultCaster::class, 'castResult'],
                Waiter::class => [ResultCaster::class, 'castWaiter'],
            ]]);
    }
}
