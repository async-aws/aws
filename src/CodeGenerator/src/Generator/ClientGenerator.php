<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

use AsyncAws\CodeGenerator\Definition\ServiceDefinition;
use AsyncAws\CodeGenerator\Generator\Composer\RequirementsRegistry;
use AsyncAws\CodeGenerator\Generator\Naming\ClassName;
use AsyncAws\CodeGenerator\Generator\Naming\NamespaceRegistry;
use AsyncAws\CodeGenerator\Generator\PhpGenerator\ClassRegistry;
use AsyncAws\Core\AwsError\AwsErrorFactoryInterface;
use AsyncAws\Core\AwsError\JsonRestAwsErrorFactory;
use AsyncAws\Core\AwsError\JsonRpcAwsErrorFactory;
use AsyncAws\Core\AwsError\XmlAwsErrorFactory;
use AsyncAws\Core\Configuration;
use AsyncAws\Core\Exception\UnsupportedRegion;
use Nette\PhpGenerator\Visibility;

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
     * @var ClassRegistry
     */
    private $classRegistry;

    /**
     * @var NamespaceRegistry
     */
    private $namespaceRegistry;

    /**
     * @var RequirementsRegistry
     */
    private $requirementsRegistry;

    public function __construct(ClassRegistry $classRegistry, NamespaceRegistry $namespaceRegistry, RequirementsRegistry $requirementsRegistry)
    {
        $this->classRegistry = $classRegistry;
        $this->namespaceRegistry = $namespaceRegistry;
        $this->requirementsRegistry = $requirementsRegistry;
    }

    /**
     * Update the API client with a constants function call.
     */
    public function generate(ServiceDefinition $definition, ?string $customErrorFactory): ClassName
    {
        $className = $this->namespaceRegistry->getClient($definition);
        if (0 !== strpos($className->getFqdn(), 'AsyncAws\Core\\')) {
            $this->requirementsRegistry->addRequirement('async-aws/core', '^1.9');
        }
        $classBuilder = $this->classRegistry->register($className->getFqdn(), true);

        $supportedVersions = eval(\sprintf('class A%s extends %s {
            public function __construct() {}
            public function getVersion() {
                return array_keys($this->getSignerFactories());
            }
        } return (new A%1$s)->getVersion();', sha1(uniqid('', true)), $className->getFqdn()));

        $endpoints = $definition->getEndpoints();
        $regionConfigs = [];
        $dumpConfig = static function ($config, $region = '$region') use ($supportedVersions) {
            $signatureVersions = array_intersect($supportedVersions, $config['signVersions']);
            rsort($signatureVersions);

            return strtr('return [
                "endpoint" => "ENDPOINT",
                "signRegion" => "REGION",
                "signService" => SIGN_SERVICE,
                "signVersions" => SIGN_VERSIONS,
            ];' . "\n", [
                'ENDPOINT' => strtr($config['endpoint'], ['%region%' => $region]),
                'REGION' => $config['signRegion'] ?? $region,
                'SIGN_SERVICE' => var_export($config['signService'], true),
                'SIGN_VERSIONS' => json_encode($signatureVersions),
            ]);
        };
        $bufferConfig = static function ($config, $region = '$region') use ($dumpConfig, &$regionConfigs) {
            $code = $dumpConfig($config, $region);

            $mapKey = hash('sha256', $code);
            $regionConfigs[$mapKey]['code'] = $code;
            $regionConfigs[$mapKey]['regions'][] = $region;
        };
        $sameConfig = static function (array $config, array $defaultConfig, string $region) use ($supportedVersions) {
            $configEndpoint = strtr($config['endpoint'], ['%region%' => $region]);
            $DefaultConfigEndpoint = strtr($defaultConfig['endpoint'], ['%region%' => $region]);
            if ($configEndpoint !== $DefaultConfigEndpoint) {
                return false;
            }

            $configRegion = $config['signRegion'] ?? $region;
            $defaultConfigRegion = $defaultConfig['signRegion'] ?? $region;
            if ($configRegion !== $defaultConfigRegion) {
                return false;
            }

            if ($config['signService'] !== $defaultConfig['signService']) {
                return false;
            }

            $configSignVersions = array_intersect($supportedVersions, $config['signVersions']);
            $defaultConfigSignVersions = array_intersect($supportedVersions, $defaultConfig['signVersions']);
            rsort($configSignVersions);
            rsort($defaultConfigSignVersions);
            if ($configSignVersions !== $defaultConfigSignVersions) {
                return false;
            }

            return true;
        };

        $body = '';
        if (!isset($endpoints['_global']['aws'])) {
            $classBuilder->addUse(Configuration::class);
            $body .= 'if ($region === null) {
                $region = Configuration::DEFAULT_REGION;
            }

            ';
        } else {
            if (empty($endpoints['_global']['aws']['signRegion'])) {
                throw new \RuntimeException('Global endpoint without signRegion is not yet supported');
            }
            $body .= 'if ($region === null) {
                ' . $dumpConfig($endpoints['_global']['aws']) . '
            }

            ';
        }

        $defaultConfig = null;
        if (isset($endpoints['_default']['aws'])) {
            $defaultConfig = $endpoints['_default']['aws'];
        } elseif (isset($endpoints['_global']['aws'])) {
            $defaultConfig = $endpoints['_global']['aws'];
        }
        foreach ($endpoints['_global'] ?? [] as $partitionName => $config) {
            if ('aws' === $partitionName && !isset($endpoints['_default']['aws'])) {
                continue;
            }
            if (empty($config['regions'])) {
                continue;
            }
            foreach ($config['regions'] as $region) {
                if ($defaultConfig && $sameConfig($config, $defaultConfig, $region)) {
                    continue;
                }
                $bufferConfig($config, $region);
            }
        }
        foreach ($endpoints['_default'] ?? [] as $partitionName => $config) {
            if ('aws' === $partitionName) {
                continue;
            }
            if (empty($config['regions'])) {
                continue;
            }
            foreach ($config['regions'] as $region) {
                if ($defaultConfig && $sameConfig($config, $defaultConfig, $region)) {
                    continue;
                }
                $bufferConfig($config, $region);
            }
        }
        foreach ($endpoints as $region => $config) {
            if ('_' === $region[0]) {
                continue; // skip `_default` and `_global`
            }
            if ($defaultConfig && $sameConfig($config, $defaultConfig, $region)) {
                continue;
            }

            $bufferConfig($config, $region);
        }

        if (!empty($regionConfigs)) {
            $body .= "switch (\$region) {\n";

            // try to group configs by dynamic $region
            $regionConfigsWithAlias = [];
            foreach ($regionConfigs as $key => $config) {
                sort($config['regions']);
                if (1 === \count($config['regions'])) {
                    $region = $config['regions'][0];
                    $code = strtr($config['code'], [$region => '$region']);
                    if ($code !== $config['code']) {
                        $mapKey = hash('sha256', $code);
                        unset($regionConfigs[$key]);
                        $regionConfigsWithAlias[$mapKey]['code'] = $code;
                        $regionConfigsWithAlias[$mapKey]['original_code'] = $config['code'];
                        $regionConfigsWithAlias[$mapKey]['regions'][] = $region;
                    }
                }
            }
            foreach ($regionConfigsWithAlias as $key => $config) {
                sort($config['regions']);
                if (1 === \count($config['regions'])) {
                    $region = $config['regions'][0];
                    $mapKey = hash('sha256', $config['original_code']);
                    $regionConfigs[$mapKey]['code'] = $config['original_code'];
                    $regionConfigs[$mapKey]['regions'][] = $region;
                } else {
                    $regionConfigs[$key] = $config;
                }
            }
            usort($regionConfigs, static function ($a, $b) {
                return [
                    false !== strpos($a['regions'][0], 'iso'),
                    false !== strpos($a['regions'][0], 'fips'),
                    false !== strpos($a['regions'][0], 'gov'),
                    $a['regions'][0],
                ] <=> [
                    false !== strpos($b['regions'][0], 'iso'),
                    false !== strpos($b['regions'][0], 'fips'),
                    false !== strpos($b['regions'][0], 'gov'),
                    $b['regions'][0],
                ];
            });
            foreach ($regionConfigs as $regionConfig) {
                $code = $regionConfig['code'];
                $regions = $regionConfig['regions'];
                foreach ($regions as $region) {
                    $body .= \sprintf("    case %s:\n", var_export($region, true));
                }
                $body .= $code;
            }

            $body .= '}';
        }

        $body .= "\n";
        if (isset($endpoints['_default']['aws'])) {
            $body .= $dumpConfig($endpoints['_default']['aws']);
        } elseif (isset($endpoints['_global']['aws'])) {
            $body .= $dumpConfig($endpoints['_global']['aws']);
        } else {
            $body .= 'throw new UnsupportedRegion(sprintf(\'The region "%s" is not supported by "' . $definition->getName() . '".\', $region));';
        }
        $classBuilder->addUse(UnsupportedRegion::class);

        $classBuilder->addMethod('getEndpointMetadata')
            ->setReturnType('array')
            ->setVisibility(Visibility::Protected)
            ->setBody(strtr($body, ['"$region"' => '$region']))
            ->addParameter('region')
                ->setType('string')
                ->setNullable(true)
        ;

        if (null !== $customErrorFactory) {
            $errorFactory = $customErrorFactory;
        } else {
            switch ($definition->getProtocol()) {
                case 'query':
                case 'rest-xml':
                    $errorFactory = XmlAwsErrorFactory::class;

                    break;
                case 'rest-json':
                    $errorFactory = JsonRestAwsErrorFactory::class;

                    break;
                case 'json':
                    $errorFactory = JsonRpcAwsErrorFactory::class;

                    break;
                default:
                    throw new \LogicException(\sprintf('Parser for "%s" is not implemented yet', $definition->getProtocol()));
            }
        }

        $classBuilder->addUse(AwsErrorFactoryInterface::class);
        $classBuilder->addUse($errorFactory);

        $errorFactoryBase = basename(str_replace('\\', '/', $errorFactory));
        $classBuilder->addMethod('getAwsErrorFactory')
            ->setReturnType(AwsErrorFactoryInterface::class)
            ->setVisibility(Visibility::Protected)
            ->setBody("return new $errorFactoryBase();")
        ;

        return $className;
    }
}
