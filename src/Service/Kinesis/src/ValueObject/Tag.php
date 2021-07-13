<?php

namespace AsyncAws\Kinesis\ValueObject;

/**
 * Metadata assigned to the stream, consisting of a key-value pair.
 */
final class Tag
{
    /**
     * A unique identifier for the tag. Maximum length: 128 characters. Valid characters: Unicode letters, digits, white
     * space, _ . / = + - % @.
     */
    private $key;

    /**
     * An optional string, typically used to describe or define the tag. Maximum length: 256 characters. Valid characters:
     * Unicode letters, digits, white space, _ . / = + - % @.
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
}
