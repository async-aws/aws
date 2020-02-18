<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

use AsyncAws\CodeGenerator\Definition\ServiceDefinition;
use AsyncAws\CodeGenerator\File\FileWriter;
use Nette\PhpGenerator\ClassType;

/**
 * Generate API client.
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 *
 * @internal
 */
class ClientGenerator
{
    /**
     * @var FileWriter
     */
    private $fileWriter;

    public function __construct(FileWriter $fileWriter)
    {
        $this->fileWriter = $fileWriter;
    }

    /**
     * Update the API client with a constants function call.
     */
    public function generate(ServiceDefinition $definition, string $service, string $baseNamespace): void
    {
        $namespace = ClassFactory::fromExistingClass(\sprintf('%s\\%sClient', $baseNamespace, $service));

        $classes = $namespace->getClasses();
        $class = $classes[\array_key_first($classes)];
        if (null !== $prefix = $definition->getEndpointPrefix()) {
            $class->addMethod('getServiceCode')
                ->setReturnType('string')
                ->setVisibility(ClassType::VISIBILITY_PROTECTED)
                ->setBody("return '$prefix';");
        }
        if (null !== $signatureVersion = $definition->getSignatureVersion()) {
            $class->addMethod('getSignatureVersion')
                ->setReturnType('string')
                ->setVisibility(ClassType::VISIBILITY_PROTECTED)
                ->setBody("return '$signatureVersion';");
        }

        $this->fileWriter->write($namespace);
    }
}
