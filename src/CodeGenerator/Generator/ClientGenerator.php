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

    /**
     * All public classes take a definition as first parameter.
     *
     * @var ServiceDefinition
     */
    private $definition;

    public function __construct(FileWriter $fileWriter, ServiceDefinition $definition)
    {
        $this->fileWriter = $fileWriter;
        $this->definition = $definition;
    }

    /**
     * Update the API client with a constants function call.
     */
    public function generate(string $service, string $baseNamespace): void
    {
        $namespace = ClassFactory::fromExistingClass(\sprintf('%s\\%sClient', $baseNamespace, $service));

        $classes = $namespace->getClasses();
        $class = $classes[\array_key_first($classes)];
        if (null !== $prefix = $this->definition->getEndpointPrefix()) {
            $class->removeMethod('getServiceCode');
            $class->addMethod('getServiceCode')
                ->setReturnType('string')
                ->setVisibility(ClassType::VISIBILITY_PROTECTED)
                ->setBody("return '$prefix';");
        }
        if (null !== $signatureVersion = $this->definition->getSignatureVersion()) {
            $class->removeMethod('getSignatureVersion');
            $class->addMethod('getSignatureVersion')
                ->setReturnType('string')
                ->setVisibility(ClassType::VISIBILITY_PROTECTED)
                ->setBody("return '$signatureVersion';");
        }

        $this->fileWriter->write($namespace);
    }
}
