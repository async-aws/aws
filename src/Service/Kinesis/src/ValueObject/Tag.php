<?php

namespace AsyncAws\Kinesis\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Metadata assigned to the stream or consumer, consisting of a key-value pair.
 */
final class Tag
{
    /**
     * A unique identifier for the tag. Maximum length: 128 characters. Valid characters: Unicode letters, digits, white
     * space, _ . / = + - % @.
     *
     * @var string
     */
    private $key;

    /**
     * An optional string, typically used to describe or define the tag. Maximum length: 256 characters. Valid characters:
     * Unicode letters, digits, white space, _ . / = + - % @
     *
     * @var string|null
     */
    private $value;

    /**
     * @param array{
     *   Key: string,
     *   Value?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->key = $input['Key'] ?? $this->throwException(new InvalidArgument('Missing required field "Key".'));
        $this->value = $input['Value'] ?? null;
    }

    /**
     * @param array{
     *   Key: string,
     *   Value?: string|null,
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
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
