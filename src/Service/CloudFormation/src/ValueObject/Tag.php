<?php

namespace AsyncAws\CloudFormation\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * The Tag type enables you to specify a key-value pair that can be used to store information about an CloudFormation
 * stack.
 */
final class Tag
{
    /**
     * A string used to identify this tag. You can specify a maximum of 128 characters for a tag key. Tags owned by Amazon
     * Web Services have the reserved prefix: `aws:`.
     *
     * @var string
     */
    private $key;

    /**
     * A string that contains the value for this tag. You can specify a maximum of 256 characters for a tag value.
     *
     * @var string
     */
    private $value;

    /**
     * @param array{
     *   Key: string,
     *   Value: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->key = $input['Key'] ?? $this->throwException(new InvalidArgument('Missing required field "Key".'));
        $this->value = $input['Value'] ?? $this->throwException(new InvalidArgument('Missing required field "Value".'));
    }

    /**
     * @param array{
     *   Key: string,
     *   Value: string,
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

    public function getValue(): string
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
