<?php

namespace AsyncAws\Iot\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * A set of key/value pairs that are used to manage the resource.
 */
final class Tag
{
    /**
     * The tag's key.
     */
    private $key;

    /**
     * The tag's value.
     */
    private $value;

    /**
     * @param array{
     *   Key: string,
     *   Value?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->key = $input['Key'] ?? null;
        $this->value = $input['Value'] ?? null;
    }

    /**
     * @param array{
     *   Key: string,
     *   Value?: null|string,
     * }|Tag $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getKey(): string
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
        if (null === $v = $this->key) {
            throw new InvalidArgument(sprintf('Missing parameter "Key" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Key'] = $v;
        if (null !== $v = $this->value) {
            $payload['Value'] = $v;
        }

        return $payload;
    }
}
