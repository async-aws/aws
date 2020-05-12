<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

use AsyncAws\CodeGenerator\Definition\ServiceDefinition;
use AsyncAws\CodeGenerator\File\FileWriter;
use AsyncAws\CodeGenerator\Generator\Naming\ClassName;
use AsyncAws\CodeGenerator\Generator\Naming\NamespaceRegistry;
use AsyncAws\CodeGenerator\Generator\PhpGenerator\ClassFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\Exception\UnsupportedRegion;
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
        $body = '';
        if (!isset($endpoints['_global']['aws'])) {
            $namespace->addUse(Configuration::class);
            $body .= 'if ($region === null) { $region = Configuration::DEFAULT_REGION; }' . \PHP_EOL;
        }
        $body .= 'switch ($region) {' . \PHP_EOL;
        $dumpConfig = function ($config) {
            sort($config['signVersions']);

            return strtr(sprintf('        return %s;' . \PHP_EOL, \var_export([
                'endpoint' => $config['endpoint'],
                'signRegion' => $config['signRegion'] ?? '%region%',
                'signService' => $config['signService'],
                'signVersions' => $config['signVersions'],
            ], true)), ['\'%region%\'' => '$region']);
        };

        foreach ($endpoints['_default'] ?? [] as $config) {
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
            if ('_' === $region[0]) {
                continue; // skip `_default` and `_global`
            }
            $body .= sprintf('    case %s:' . \PHP_EOL, \var_export($region, true));
            $body .= $dumpConfig($config);
        }
        foreach ($endpoints['_global'] ?? [] as $partition => $config) {
            if (empty($config['regions'])) {
                continue;
            }
            if ('aws' === $partition) {
                continue; // skip `aws` partition which is the default case.
            }
            sort($config['regions']);
            foreach ($config['regions'] as $region) {
                $body .= sprintf('    case %s:' . \PHP_EOL, \var_export($region, true));
            }
            $body .= $dumpConfig($config);
        }
        $body .= '}' . \PHP_EOL;

        if (isset($endpoints['_global']['aws'])) {
            $body .= $dumpConfig($endpoints['_global']['aws']);
        } else {
            $namespace->addUse(UnsupportedRegion::class);
            $body .= 'throw new UnsupportedRegion(sprintf(\'The region "%s" is not supported by "' . $definition->getName() . '".\', $region));';
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
