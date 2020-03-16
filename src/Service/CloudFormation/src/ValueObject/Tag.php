<?php

namespace AsyncAws\CloudFormation\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

class Tag
{
    /**
     * *Required*. A string used to identify this tag. You can specify a maximum of 128 characters for a tag key. Tags owned
     * by Amazon Web Services (AWS) have the reserved prefix: `aws:`.
     */
    private $Key;

    /**
     * *Required*. A string containing the value for this tag. You can specify a maximum of 256 characters for a tag value.
     */
    private $Value;

    /**
     * @param array{
     *   Key: string,
     *   Value: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Key = $input['Key'] ?? null;
        $this->Value = $input['Value'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getKey(): string
    {
        return $this->Key;
    }

    public function getValue(): string
    {
        return $this->Value;
    }

    public function validate(): void
    {
        if (null === $this->Key) {
            throw new InvalidArgument(sprintf('Missing parameter "Key" when validating the "%s". The value cannot be null.', __CLASS__));
        }

        if (null === $this->Value) {
            throw new InvalidArgument(sprintf('Missing parameter "Value" when validating the "%s". The value cannot be null.', __CLASS__));
        }
    }
}
