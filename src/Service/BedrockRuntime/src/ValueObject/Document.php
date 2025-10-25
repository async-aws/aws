<?php

namespace AsyncAws\BedrockRuntime\ValueObject;

final class Document
{
    /**
     * @param array|Document $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self();
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];

        return $payload;
    }
}
