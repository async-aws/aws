<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

use AsyncAws\CodeGenerator\Definition\Shape;
use AsyncAws\CodeGenerator\Generator\Naming\ClassName;
use AsyncAws\CodeGenerator\Generator\Naming\NamespaceRegistry;
use AsyncAws\CodeGenerator\Generator\PhpGenerator\ClassRegistry;
use Nette\PhpGenerator\Visibility;

/**
 * Generate Enum shapeused by Input and Result classes.
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 *
 * @internal
 */
class EnumGenerator
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
     * @var ClassName[]
     */
    private $generated = [];

    public function __construct(ClassRegistry $classRegistry, NamespaceRegistry $namespaceRegistry)
    {
        $this->classRegistry = $classRegistry;
        $this->namespaceRegistry = $namespaceRegistry;
    }

    /**
     * Generate classes for the input. Ie, the request of the API call.
     */
    public function generate(Shape $shape): ClassName
    {
        if (isset($this->generated[$shape->getName()])) {
            return $this->generated[$shape->getName()];
        }

        $this->generated[$shape->getName()] = $className = $this->namespaceRegistry->getEnum($shape);

        $classBuilder = $this->classRegistry->register($className->getFqdn());
        $classBuilder->setFinal();
        if (null !== $documentation = $shape->getDocumentationMain()) {
            $classBuilder->addComment(GeneratorHelper::parseDocumentation($documentation));
        }

        $consts = [];
        foreach ($shape->getEnum() as $value) {
            $consts[self::canonicalizeName($value)] = $value;
        }
        ksort($consts);
        $availableConsts = [];
        foreach ($consts as $constName => $constValue) {
            $classBuilder->addConstant($constName, $constValue)->setVisibility(Visibility::Public);
            $availableConsts[] = 'self::' . $constName . ' => true';
        }
        $classBuilder->addMethod('exists')
            ->setStatic(true)
            ->setReturnType('bool')
            ->setBody('
                return isset([
                    ' . implode(",\n", $availableConsts) . '
                ][$value]);
            ')
            ->addParameter('value')->setType('string');

        return $className;
    }

    public static function canonicalizeName(string $name): string
    {
        // java10 => JAVA_10
        // go1.x => GO_1_X
        // s3:ObjectCreated:* => S3_OBJECT_CREATED_ALL
        $name = strtr($name, ['*' => '_ALL']);
        $name = strtoupper(preg_replace('/([a-z])([A-Z\d])/', '\\1_\\2', $name));
        $name = preg_replace('/[^A-Z\d ]+/', '_', $name);

        $replacements = [
            'S_3' => 'S3',
            'EC_2' => 'EC2',
            'CLOUD_9' => 'CLOUD9',
            'ROUTE_53' => 'ROUTE53',
            'ARM_64' => 'ARM64',
            'X_86_64' => 'X86_64',
        ];
        foreach ($replacements as $old => $new) {
            $name = preg_replace('/(^|_)' . $old . '(_|$)/', '\\1' . $new . '\\2', $name);
        }

        return $name;
    }
}
