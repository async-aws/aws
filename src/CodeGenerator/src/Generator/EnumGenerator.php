<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

use AsyncAws\CodeGenerator\Definition\Shape;
use AsyncAws\CodeGenerator\File\FileWriter;
use AsyncAws\CodeGenerator\Generator\Naming\ClassName;
use AsyncAws\CodeGenerator\Generator\Naming\NamespaceRegistry;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;

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
     * @var NamespaceRegistry
     */
    private $namespaceRegistry;

    /**
     * @var FileWriter
     */
    private $fileWriter;

    /**
     * @var ClassName[]
     */
    private $generated = [];

    public function __construct(NamespaceRegistry $namespaceRegistry, FileWriter $fileWriter)
    {
        $this->namespaceRegistry = $namespaceRegistry;
        $this->fileWriter = $fileWriter;
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

        $namespace = new PhpNamespace($className->getNamespace());
        $class = $namespace->addClass($className->getName());
        $class->setFinal();

        $consts = [];
        foreach ($shape->getEnum() as $value) {
            $consts[self::canonicalizeName($value)] = $value;
        }
        \ksort($consts);
        $availableConsts = [];
        foreach ($consts as $constName => $constValue) {
            $class->addConstant($constName, $constValue)->setVisibility(ClassType::VISIBILITY_PUBLIC);
            $availableConsts[] = 'self::' . $constName . ' => true';
        }
        $class->addMethod('exists')
            ->setStatic(true)
            ->setReturnType('bool')
            ->setBody('
                return isset([
                    ' . implode(",\n", $availableConsts) . '
                ][$value]);
            ')
            ->addParameter('value')->setType('string');

        $this->fileWriter->write($namespace);

        return $className;
    }

    public static function canonicalizeName(string $name): string
    {
        // java10 => JAVA_10
        // go1.x => GO_1_X
        // s3:ObjectCreated:* => S3_OBJECT_CREATED_ALL
        $name = \strtr($name, ['*' => '_ALL']);
        $name = strtoupper(\preg_replace('/([a-z])([A-Z\d])/', '\\1_\\2', $name));
        $name = preg_replace('/[^A-Z\d ]+/', '_', $name);

        $replacements = [
            'S_3' => 'S3',
            'EC_2' => 'EC2',
            'CLOUD_9' => 'CLOUD9',
            'ROUTE_53' => 'ROUTE53',
        ];
        foreach ($replacements as $old => $new) {
            $name = preg_replace('/(^|_)' . $old . '(_|$)/', '\\1' . $new . '\\2', $name);
        }

        return $name;
    }
}
