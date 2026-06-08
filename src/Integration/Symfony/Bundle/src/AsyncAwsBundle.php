<?php

declare(strict_types=1);

namespace AsyncAws\Symfony\Bundle;

use AsyncAws\Symfony\Bundle\DependencyInjection\AsyncAwsExtension;
use AsyncAws\Symfony\Bundle\DependencyInjection\Compiler\InjectCasterPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Kernel\AbstractBundle;
use Symfony\Component\HttpKernel\Bundle\Bundle;

if (class_exists(AbstractBundle::class)) {
    /**
     * @internal
     */
    abstract class BaseBundle extends AbstractBundle
    {
    }
} else {
    /**
     * @internal
     */
    abstract class BaseBundle extends Bundle
    {
    }
}

class AsyncAwsBundle extends BaseBundle
{
    private ?ExtensionInterface $asyncAwsExtension = null;

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new InjectCasterPass());
    }

    /**
     * Return the conventional extension explicitly: the modern AbstractBundle base class would
     * otherwise auto-register an empty BundleExtension and ignore AsyncAwsExtension.
     */
    public function getContainerExtension(): ?ExtensionInterface
    {
        return $this->asyncAwsExtension ??= new AsyncAwsExtension();
    }
}
