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

        $endpoints = $definition->getEndpoints();
        $body = 'switch ($region) {' . \PHP_EOL;
        $default = $endpoints['_default'] ?? [];
        $global = $endpoints['_global'] ?? [];
        unset($endpoints['_default'], $endpoints['_global']);
        $dumpConfig = function ($config) {
            sort($config['signVersions']);

            return strtr(sprintf('        return %s;' . \PHP_EOL, \var_export([
                'endpoint' => $config['endpoint'],
                'signRegion' => $config['signRegion'] ?? '%region%',
                'signService' => $config['signService'],
                'signVersions' => $config['signVersions'],
            ], true)), ['\'%region%\'' => '$region']);
        };
        foreach ($default as $config) {
            if (empty($config['regions'])) {
                continue;
            }
            sort($config['regions']);
            foreach ($config['regions'] as $region) {
                $body .= sprintf('    case %s:' . \PHP_EOL, \var_export($region, true));
            }
            $body .= $dumpConfig($config);
        }
        ksort($endpoints);
        foreach ($endpoints as $region => $config) {
            $body .= sprintf('    case %s:' . \PHP_EOL, \var_export($region, true));
            $body .= $dumpConfig($config);
        }
        foreach ($global as $config) {
            if (empty($config['regions'])) {
                continue;
            }
            sort($config['regions']);
            foreach ($config['regions'] as $region) {
                $body .= sprintf('    case %s:' . \PHP_EOL, \var_export($region, true));
            }
            $body .= $dumpConfig($config);
        }
        $body .= '}' . \PHP_EOL;

        if (isset($global['aws'])) {
            $body .= $dumpConfig($global['aws']);
        } else {
            $body .= 'throw new \\InvalidArgumentException(sprintf(\'The region "%s" is not supported by "' . $definition->getName() . '".\', $region));';
        }

        $class->addMethod('getEndpointMetadata')
            ->setReturnType('array')
            ->setVisibility(ClassType::VISIBILITY_PROTECTED)
            ->setBody($body)
            ->addParameter('region')
                ->setType('string')
                ->setNullable(true)
        ;

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
