<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Generator;

use AsyncAws\CodeGenerator\Definition\ListShape;
use AsyncAws\CodeGenerator\Definition\MapShape;
use AsyncAws\CodeGenerator\Definition\StructureShape;
use AsyncAws\Core\Exception\InvalidArgument;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;

/**
 * Generate Validator method.
 *
 * @author Tobias Nyholm <tobias.nyholm@gmail.com>
 * @author Jérémy Derussé <jeremy@derusse.com>
 *
 * @internal
 */
trait ValidableTrait
{
    private function generateValidate(StructureShape $shape, ClassType $class, PhpNamespace $namespace): void
    {
        // Add validate()
        $namespace->addUse(InvalidArgument::class);
        $validateBody = [];
        foreach ($shape->getMembers() as $member) {
            $memberShape = $member->getShape();
            $memberValidate = [];
            $required = $member->isRequired();
            $nullable = true;

            if ($memberShape instanceof StructureShape) {
                $memberValidate[] = \strtr('$this->PROPERTY->validate();', [
                    'PROPERTY' => $member->getName(),
                ]);
            } elseif ($memberShape instanceof ListShape) {
                $nullable = false;
                $listMemberShape = $memberShape->getMember()->getShape();
                $itemValidate = [];
                if ($listMemberShape instanceof StructureShape) {
                    $itemValidate[] = '$item->validate();';
                }
                if (!empty($listMemberShape->getEnum())) {
                    $enumClassName = $this->enumGenerator->generate($listMemberShape);

                    $itemValidate[] = \strtr('if (!ENUMCLASS::exists($item)) {
                        throw new InvalidArgument(sprintf(\'Invalid parameter "PROPERTY" when validating the "%s". The value "%s" is not a valid "ENUMCLASS".\', __CLASS__, $item));
                    }', [
                        'ENUMCLASS' => $enumClassName->getName(),
                        'PROPERTY' => $member->getName(),

                    ]);
                }
                if (!empty($itemValidate)) {
                    $memberValidate[] = \strtr('foreach ($this->PROPERTY as $item) {
                        VALIDATE
                    }', [
                        'VALIDATE' => implode("\n", $itemValidate),
                        'PROPERTY' => $member->getName(),

                    ]);
                }
            } elseif ($memberShape instanceof MapShape) {
                $nullable = false;
                $mapValueShape = $memberShape->getValue()->getShape();
                $itemValidate = [];
                if ($mapValueShape instanceof StructureShape) {
                    $itemValidate[] = '$item->validate();';
                }
                if (!empty($mapValueShape->getEnum())) {
                    $enumClassName = $this->enumGenerator->generate($mapValueShape);

                    $itemValidate[] = \strtr('if (!ENUMCLASS::exists($item)) {
                        throw new InvalidArgument(sprintf(\'Invalid parameter "PROPERTY" when validating the "%s". The value "%s" is not a valid "ENUMCLASS".\', __CLASS__, $item));
                    }', [
                        'ENUMCLASS' => $enumClassName->getName(),
                        'PROPERTY' => $member->getName(),
                    ]);
                }
                if (!empty($itemValidate)) {
                    $memberValidate[] = \strtr('foreach ($this->PROPERTY as $item) {
                        VALIDATE
                    }', [
                        'VALIDATE' => implode("\n", $itemValidate),
                        'PROPERTY' => $member->getName(),

                    ]);
                }
            } else {
                if (!empty($memberShape->getEnum())) {
                    $enumClassName = $this->enumGenerator->generate($memberShape);

                    $memberValidate[] = \strtr('if (!ENUMCLASS::exists($this->PROPERTY)) {
                        throw new InvalidArgument(sprintf(\'Invalid parameter "PROPERTY" when validating the "%s". The value "%s" is not a valid "ENUMCLASS".\', __CLASS__, $this->PROPERTY));
                    }', [
                        'ENUMCLASS' => $enumClassName->getName(),
                        'PROPERTY' => $member->getName(),
                    ]);
                }
            }

            if ($required && $nullable) {
                $validateBody[] = strtr('if (null === $this->PROPERTY) {
                    throw new InvalidArgument(sprintf(\'Missing parameter "PROPERTY" when validating the "%s". The value cannot be null.\', __CLASS__));
                }
                VALIDATE', [
                    'PROPERTY' => $member->getName(),
                    'VALIDATE' => implode("\n\n", $memberValidate),
                ]);
            } elseif (!empty($memberValidate)) {
                if ($nullable) {
                    $validateBody[] = strtr('if (null !== $this->PROPERTY) {
                        VALIDATE
                    }', [
                        'PROPERTY' => $member->getName(),
                        'VALIDATE' => implode("\n\n", $memberValidate),
                    ]);
                } else {
                    $validateBody[] = implode("\n\n", $memberValidate);
                }
            }
        }

        $class->addMethod('validate')->setPublic()->setReturnType('void')->setBody(empty($validateBody) ? '// There are no required properties' : \implode("\n\n", $validateBody));
    }
}
