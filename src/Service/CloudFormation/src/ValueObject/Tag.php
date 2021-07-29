<?php

namespace AsyncAws\CloudFormation\ValueObject;

/**
 * The Tag type enables you to specify a key-value pair that can be used to store information about an CloudFormation
 * stack.
 */
final class Tag
{
    /**
     * *Required*. A string used to identify this tag. You can specify a maximum of 128 characters for a tag key. Tags owned
     * by Amazon Web Services (Amazon Web Services) have the reserved prefix: `aws:`.
     */
    private $key;

    /**
     * *Required*. A string containing the value for this tag. You can specify a maximum of 256 characters for a tag value.
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

    public function getValue(): string
    {
        return $this->value;
    }
}
