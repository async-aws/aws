<?php

namespace AsyncAws\CloudFormation\Result;

class Tag
{
    private $Key;

    private $Value;

    /**
     * @param array{
     *   Key: string,
     *   Value: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Key = $input['Key'];
        $this->Value = $input['Value'];
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * *Required*. A string used to identify this tag. You can specify a maximum of 128 characters for a tag key. Tags owned
     * by Amazon Web Services (AWS) have the reserved prefix: `aws:`.
     */
    public function getKey(): string
    {
        return $this->Key;
    }

    /**
     * *Required*. A string containing the value for this tag. You can specify a maximum of 256 characters for a tag value.
     */
    public function getValue(): string
    {
        return $this->Value;
    }
}
