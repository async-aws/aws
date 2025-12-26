<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

use AsyncAws\CodeGenerator\Definition\ListShape;
use AsyncAws\CodeGenerator\Definition\MapShape;
use AsyncAws\CodeGenerator\Definition\ObjectShape;
use AsyncAws\CodeGenerator\Definition\Shape;
use AsyncAws\CodeGenerator\Definition\StructureShape;
use AsyncAws\CodeGenerator\Definition\UnionShape;

/**
 * Provides method to test if a shaped is used in input or output.
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 *
 * @internal
 */
class ShapeUsageHelper
{
    /**
     * @var list<string>
     */
    private $managedOperations = [];

    /**
     * @var array<string, bool>|null
     */
    private $usedShapedInput;

    /**
     * @var array<string, bool>|null
     */
    private $usedShapedOutput;

    public function __construct(array $managedOperations)
    {
        $this->managedOperations = $managedOperations;
    }

    public function isShapeUsedInput(ObjectShape $shape): bool
    {
        if (null === $this->usedShapedInput) {
            $service = $shape->getService();
            $this->usedShapedInput = [];

            foreach ($this->managedOperations as $method) {
                if (null !== $operation = $service->getOperation($method)) {
                    $this->walkShape($operation->getInput(), $this->usedShapedInput);
                }
                if (null !== $waiter = $service->getWaiter($method)) {
                    $this->walkShape($waiter->getOperation()->getInput(), $this->usedShapedInput);
                }
            }
        }

        return $this->usedShapedInput[$shape->getName()] ?? false;
    }

    public function isShapeUsedOutput(Shape $shape): bool
    {
        if (null === $this->usedShapedOutput) {
            $service = $shape->getService();
            $this->usedShapedOutput = [];

            foreach ($this->managedOperations as $method) {
                if (null !== $operation = $service->getOperation($method)) {
                    $this->walkShape($operation->getOutput(), $this->usedShapedOutput);
                    foreach ($operation->getErrors() as $error) {
                        $this->walkShape($error, $this->usedShapedOutput);
                    }
                }
                if (null !== $waiter = $service->getWaiter($method)) {
                    $this->walkShape($waiter->getOperation()->getOutput(), $this->usedShapedOutput);
                    foreach ($waiter->getOperation()->getErrors() as $error) {
                        $this->walkShape($error, $this->usedShapedOutput);
                    }
                }
            }
        }

        return $this->usedShapedOutput[$shape->getName()] ?? false;
    }

    /**
     * @param array<string, bool> $marker
     */
    private function walkShape(?Shape $shape, array &$marker): void
    {
        if (null === $shape) {
            return;
        }
        if (isset($marker[$shape->getName()])) {
            // Node already visited
            return;
        }

        $marker[$shape->getName()] = true;
        if ($shape instanceof StructureShape) {
            foreach ($shape->getMembers() as $member) {
                $this->walkShape($member->getShape(), $marker);
            }
        } elseif ($shape instanceof UnionShape) {
            foreach ($shape->getChildren() as $child) {
                $this->walkShape($child, $marker);
            }
        } elseif ($shape instanceof ListShape) {
            $this->walkShape($shape->getMember()->getShape(), $marker);
        } elseif ($shape instanceof MapShape) {
            $this->walkShape($shape->getKey()->getShape(), $marker);
            $this->walkShape($shape->getValue()->getShape(), $marker);
        }
    }
}
