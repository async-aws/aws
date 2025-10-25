<?php

namespace AsyncAws\BedrockRuntime\ValueObject;

/**
 * The model must request at least one tool (no text is generated). For example, `{"any" : {}}`.
 */
final class AnyToolChoice
{
    /**
     * @param array|AnyToolChoice $input
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
