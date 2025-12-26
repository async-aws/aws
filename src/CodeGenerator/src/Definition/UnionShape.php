<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Definition;

/**
 * @internal
 */
class UnionShape extends ObjectShape
{
    public function getChildren(): array
    {
        $children = [];
        foreach ($this->data['members'] as $name => $member) {
            $locationName = $member['locationName'] ?? $name;
            $children[$locationName] = Shape::create(
                $this->getChildName($name),
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

    public function getChildForUnknown(): StructureShape
    {
        /** @phpstan-ignore return.type */
        return Shape::create(
            $this->getChildName('UnknownToSdk'),
            [
                'type' => 'structure',
                '_members_owner' => $this,
                'required' => [],
                'members' => [],
            ],
            $this->shapeLocator,
            $this->serviceLocator,
        );
    }

    private function getChildName(string $name): string
    {
        return $this->getName() . 'Member' . ucfirst($name);
    }
}
