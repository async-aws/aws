<?php

namespace AsyncAws\BedrockRuntime\ValueObject;

/**
 * The schema for the tool. The top level schema type must be `object`.
 */
final class ToolInputSchema
{
    /**
     * The JSON schema for the tool. For more information, see JSON Schema Reference [^1].
     *
     * [^1]: https://json-schema.org/understanding-json-schema/reference
     *
     * @var Document|null
     */
    private $json;

    /**
     * @param array{
     *   json?: null|Document|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->json = isset($input['json']) ? Document::create($input['json']) : null;
    }

    /**
     * @param array{
     *   json?: null|Document|array,
     * }|ToolInputSchema $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getJson(): ?Document
    {
        return $this->json;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->json) {
            $payload['json'] = $v->requestBody();
        }

        return $payload;
    }
}
