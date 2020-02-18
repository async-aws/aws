<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Definition;

class StructureMember extends Member
{
    public function getName(): string
    {
        return $this->data['_name'];
    }

    public function getOwnerShape(): StructureShape
    {
        return $this->data['_owner'];
    }

    public function isRequired(): bool
    {
        return $this->data['_required'];
    }

    public function isStreaming(): bool
    {
        return $this->data['streaming'] ?? false;
    }

    public function isXmlAttribute(): bool
    {
        return $this->data['xmlAttribute'] ?? false;
    }

    public function getLocation(): ?string
    {
        return $this->data['location'] ?? null;
    }

    public function getXmlNamespaceUri(): ?string
    {
        return $this->data['xmlNamespace']['uri'] ?? null;
    }
}
