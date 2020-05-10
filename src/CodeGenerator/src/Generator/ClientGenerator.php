<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

use AsyncAws\CodeGenerator\Definition\ServiceDefinition;
use AsyncAws\CodeGenerator\File\FileWriter;
use AsyncAws\CodeGenerator\Generator\Naming\ClassName;
use AsyncAws\CodeGenerator\Generator\Naming\NamespaceRegistry;
use AsyncAws\CodeGenerator\Generator\PhpGenerator\ClassFactory;
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
     * @var NamespaceRegistry
     */
    private $namespaceRegistry;

    /**
     * @var FileWriter
     */
    private $fileWriter;

    public function __construct(NamespaceRegistry $namespaceRegistry, FileWriter $fileWriter)
    {
        $this->namespaceRegistry = $namespaceRegistry;
        $this->fileWriter = $fileWriter;
    }

    /**
     * Update the API client with a constants function call.
     */
    public function generate(ServiceDefinition $definition): ClassName
    {
        $className = $this->namespaceRegistry->getClient($definition);
        $namespace = ClassFactory::fromExistingClass($className->getFqdn());

        $classes = $namespace->getClasses();
        $class = $classes[\array_key_first($classes)];
        if (null !== $prefix = $definition->getEndpointPrefix()) {
            $class->addMethod('getServiceCode')
                ->setReturnType('string')
                ->setVisibility(ClassType::VISIBILITY_PROTECTED)
                ->setBody("return '$prefix';");
        }
        if (null !== $endpoint = $definition->getGlobalEndpoint()) {
            $class->addMethod('getEndpointPattern')
                ->setReturnType('string')
                ->setVisibility(ClassType::VISIBILITY_PROTECTED)
                ->setBody("return \$region ? parent::getEndpointPattern(\$region) : 'https://$endpoint';")
                ->addParameter('region')
                    ->setType('string')
                    ->setNullable(true)
            ;
        }
        if (null !== $signatureVersion = $definition->getSignatureVersion()) {
            $class->addMethod('getSignatureVersion')
                ->setReturnType('string')
                ->setVisibility(ClassType::VISIBILITY_PROTECTED)
                ->setBody("return '$signatureVersion';");
        }
        if (null !== $signingName = $definition->getSigningName()) {
            $class->addMethod('getSignatureScopeName')
                ->setReturnType('string')
                ->setVisibility(ClassType::VISIBILITY_PROTECTED)
                ->setBody("return '$signingName';");
        }

        $this->fileWriter->write($namespace);

        return $className;
    }
}
