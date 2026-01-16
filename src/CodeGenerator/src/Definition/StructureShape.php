<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Definition;

/**
 * @internal
 */
class StructureShape extends ObjectShape
{
    /**
     * @return StructureMember[]
     */
    public function getMembers(): array
    {
        $required = $this->data['required'] ?? [];
        $members = [];
        foreach ($this->data['members'] as $name => $member) {
            $members[] = new StructureMember(
                $member + ['_name' => $name, '_owner' => $this->data['_members_owner'] ?? $this, '_required' => \in_array($name, $required)],
                $this->shapeLocator
            );
        }

        return $members;
    }

    public function getMember(string $name): StructureMember
    {
        if (!isset($this->data['members'][$name])) {
            throw new \InvalidArgumentException(\sprintf('The member "%s" does not exists.', $name));
        }

        return new StructureMember(
            $this->data['members'][$name] + ['_name' => $name, '_owner' => $this, '_required' => \in_array($name, $this->data['required'] ?? [])],
            $this->shapeLocator
        );
    }

    /**
     * @return string[]
     */
    public function getRequired(): array
    {
        return $this->data['required'] ?? [];
    }

    public function getPayload(): ?string
    {
        return $this->data['payload'] ?? null;
    }

    public function getXmlNamespaceUri(): ?string
    {
        return $this->data['xmlNamespace']['uri'] ?? null;
    }

    public function getXmlNamespacePrefix(): ?string
    {
        return $this->data['xmlNamespace']['prefix'] ?? null;
    }

    public function getResultWrapper(): ?string
    {
        return $this->data['resultWrapper'] ?? null;
    }
}
