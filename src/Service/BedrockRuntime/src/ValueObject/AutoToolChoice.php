<?php

namespace AsyncAws\BedrockRuntime\ValueObject;

/**
 * The Model automatically decides if a tool should be called or whether to generate text instead. For example, `{"auto"
 * : {}}`.
 */
final class AutoToolChoice
{
    /**
     * @param array|AutoToolChoice $input
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
