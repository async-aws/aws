<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Definition;

/**
 * @internal
 */
class UnionShape extends StructureShape
{
    public function getChildren(): array
    {
        $children = [];
        foreach ($this->data['members'] as $name => $member) {
            $children[$name] = Shape::create(
                $this->getName() . 'Member' . ucfirst($name),
                [
                    'type' => 'structure',
                    '_members_owner' => $this,
                    'required' => [$name],
                    'members' => [$name => $member],
                ],
                $this->shapeLocator,
                $this->serviceLocator,
            );
        }

        return $children;
    }
}
