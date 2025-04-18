<?php

namespace AsyncAws\BedrockRuntime\ValueObject;

/**
 * Contains the content of a document.
 */
final class DocumentSource
{
    /**
     * The raw bytes for the document. If you use an Amazon Web Services SDK, you don't need to encode the bytes in base64.
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
     * }|DocumentSource $input
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
