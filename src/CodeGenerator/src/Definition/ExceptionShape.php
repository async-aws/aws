<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Definition;

class ExceptionShape extends StructureShape
{
    public function hasError(): bool
    {
        return isset($this->data['error']);
    }

    public function getCode(): ?string
    {
        return $this->data['error']['code'] ?? null;
    }

    public function getStatusCode(): ?int
    {
        return $this->data['error']['httpStatusCode'] ?? 400;
    }

    public function isSenderFault(): ?bool
    {
        if (isset($this->data['error']['senderFault'])) {
            return $this->data['error']['senderFault'];
        }
        if (isset($this->data['error']['fault'])) {
            return !$this->data['error']['fault'];
        }
        if (isset($this->data['error']['httpStatusCode'])) {
            return $this->data['error']['httpStatusCode'] < 500;
        }

        // it's always user's fault!
        return true;
    }
}
