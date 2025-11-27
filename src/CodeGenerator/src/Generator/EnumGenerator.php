<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

use AsyncAws\CodeGenerator\Definition\ListShape;
use AsyncAws\CodeGenerator\Definition\MapShape;
use AsyncAws\CodeGenerator\Definition\Shape;
use AsyncAws\CodeGenerator\Definition\StructureShape;
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
    public const UNKNOWN_VALUE = 'UNKNOWN_TO_SDK';

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

    /**
     * @var list<string>
     */
    private $managedMethods;

    /**
     * @var array<string, bool>|null
     */
    private $usedShapedOutput;

    public function __construct(ClassRegistry $classRegistry, NamespaceRegistry $namespaceRegistry, array $managedMethods)
    {
        $this->classRegistry = $classRegistry;
        $this->namespaceRegistry = $namespaceRegistry;
        $this->managedMethods = $managedMethods;
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

        if ($this->isShapeUsedOutput($shape)) {
            $classBuilder->addConstant(self::UNKNOWN_VALUE, self::UNKNOWN_VALUE)->setVisibility(Visibility::Public);
        }
        $classBuilder->addMethod('exists')
            ->setStatic(true)
            ->setReturnType('bool')
            ->setComment('@psalm-assert-if-true self::* $value')
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

    private function isShapeUsedOutput(Shape $shape): bool
    {
        if (null === $this->usedShapedOutput) {
            $service = $shape->getService();
            $walk = function (?Shape $shape) use (&$walk) {
                if (null === $shape) {
                    return;
                }
                if (isset($this->usedShapedOutput[$shape->getName()])) {
                    // Node already visited
                    return;
                }

                $this->usedShapedOutput[$shape->getName()] = true;
                if ($shape instanceof StructureShape) {
                    foreach ($shape->getMembers() as $member) {
                        $walk($member->getShape());
                    }
                } elseif ($shape instanceof ListShape) {
                    $walk($shape->getMember()->getShape());
                } elseif ($shape instanceof MapShape) {
                    $walk($shape->getKey()->getShape());
                    $walk($shape->getValue()->getShape());
                }
            };

            foreach ($this->managedMethods as $method) {
                if (null !== $operation = $service->getOperation($method)) {
                    $walk($operation->getOutput());
                    foreach ($operation->getErrors() as $error) {
                        $walk($error);
                    }
                }
                if (null !== $waiter = $service->getWaiter($method)) {
                    $walk($waiter->getOperation()->getOutput());
                    foreach ($waiter->getOperation()->getErrors() as $error) {
                        $walk($error);
                    }
                }
            }
        }

        return $this->usedShapedOutput[$shape->getName()] ?? false;
    }
}
