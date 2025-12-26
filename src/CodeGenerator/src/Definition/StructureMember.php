<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Definition;

/**
 * @internal
 */
class StructureMember extends Member
{
    public function getName(): string
    {
        if ($this->getOwnerShape() instanceof ExceptionShape && \in_array(strtolower($this->data['_name']), ['code', 'message'], true)) {
            return strtolower($this->data['_name']);
        }

        return $this->data['_name'];
    }

    public function getOwnerShape(): ObjectShape
    {
        return $this->data['_owner'];
    }

    public function isRequired(): bool
    {
        if ($this->getOwnerShape() instanceof ExceptionShape && \in_array(strtolower($this->data['_name']), ['code', 'message'], true)) {
            return false;
        }

        return $this->data['_required'];
    }

    public function isIdempotencyToken(): bool
    {
        return $this->data['idempotencyToken'] ?? false;
    }

    public function isNullable(): bool
    {
        if ($this->getOwnerShape() instanceof ExceptionShape && \in_array(strtolower($this->data['_name']), ['code', 'message'], true)) {
            return false;
        }

        return true;
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

    public function getXmlNamespacePrefix(): ?string
    {
        return $this->data['xmlNamespace']['prefix'] ?? null;
    }
}
