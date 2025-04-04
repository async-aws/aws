<?php

namespace AsyncAws\BedrockRuntime\ValueObject;

/**
 * The image source (image bytes) of the guardrail converse image source.
 */
final class GuardrailConverseImageSource
{
    /**
     * The raw image bytes for the image.
     *
     * @var string|null
     */
    private $bytes;

    /**
     * @param array{
     *   bytes?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->bytes = $input['bytes'] ?? null;
    }

    /**
     * @param array{
     *   bytes?: null|string,
     * }|GuardrailConverseImageSource $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getBytes(): ?string
    {
        return $this->bytes;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->bytes) {
            $payload['bytes'] = base64_encode($v);
        }

        return $payload;
    }
}
