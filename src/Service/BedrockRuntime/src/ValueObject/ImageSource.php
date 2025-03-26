<?php

namespace AsyncAws\BedrockRuntime\ValueObject;

/**
 * The source for an image.
 */
final class ImageSource
{
    /**
     * The raw image bytes for the image. If you use an AWS SDK, you don't need to encode the image bytes in base64.
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
     * }|ImageSource $input
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
