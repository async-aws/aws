<?php

declare(strict_types=1);

namespace AsyncAws\CodeGenerator\Definition;

class ExceptionShape extends Shape
{
    public function getCode(): ?string
    {
        return $this->data['error']['code'] ?? null;
    }

    public function getStatusCode(): ?int
    {
        return $this->data['error']['httpStatusCode'] ?? null;
    }
}
