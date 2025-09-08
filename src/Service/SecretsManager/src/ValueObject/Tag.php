<?php

namespace AsyncAws\SecretsManager\ValueObject;

/**
 * A structure that contains information about a tag.
 */
final class Tag
{
    /**
     * The key identifier, or name, of the tag.
     *
     * @var string|null
     */
    private $key;

    /**
     * The string value associated with the key of the tag.
     *
     * @var string|null
     */
    private $value;

    /**
     * @param array{
     *   Key?: string|null,
     *   Value?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->key = $input['Key'] ?? null;
        $this->value = $input['Value'] ?? null;
    }

    /**
     * @param array{
     *   Key?: string|null,
     *   Value?: string|null,
     * }|Tag $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->key) {
            $payload['Key'] = $v;
        }
        if (null !== $v = $this->value) {
            $payload['Value'] = $v;
        }

        return $payload;
    }
}
