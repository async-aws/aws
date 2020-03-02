<?php

namespace AsyncAws\Sqs\Result;

class MessageAttributeValue
{
    private $StringValue;

    private $BinaryValue;

    private $StringListValues = [];

    private $BinaryListValues = [];

    private $DataType;

    /**
     * @param array{
     *   StringValue: null|string,
     *   BinaryValue: null|string,
     *   StringListValues: null|string[],
     *   BinaryListValues: null|string[],
     *   DataType: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->StringValue = $input['StringValue'];
        $this->BinaryValue = $input['BinaryValue'];
        $this->StringListValues = $input['StringListValues'] ?? [];
        $this->BinaryListValues = $input['BinaryListValues'] ?? [];
        $this->DataType = $input['DataType'];
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return string[]
     */
    public function getBinaryListValues(): array
    {
        return $this->BinaryListValues;
    }

    /**
     * Binary type attributes can store any binary data, such as compressed data, encrypted data, or images.
     */
    public function getBinaryValue(): ?string
    {
        return $this->BinaryValue;
    }

    /**
     * Amazon SQS supports the following logical data types: `String`, `Number`, and `Binary`. For the `Number` data type,
     * you must use `StringValue`.
     */
    public function getDataType(): string
    {
        return $this->DataType;
    }

    /**
     * @return string[]
     */
    public function getStringListValues(): array
    {
        return $this->StringListValues;
    }

    /**
     * Strings are Unicode with UTF-8 binary encoding. For a list of code values, see ASCII Printable Characters.
     *
     * @see http://en.wikipedia.org/wiki/ASCII#ASCII_printable_characters
     */
    public function getStringValue(): ?string
    {
        return $this->StringValue;
    }
}
