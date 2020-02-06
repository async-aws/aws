<?php

namespace AsyncAws\Sqs\Result;

class MessageAttributeValue
{
    /**
     * Strings are Unicode with UTF-8 binary encoding. For a list of code values, see ASCII Printable Characters.
     *
     * @see http://en.wikipedia.org/wiki/ASCII#ASCII_printable_characters
     */
    private $StringValue;

    /**
     * Binary type attributes can store any binary data, such as compressed data, encrypted data, or images.
     */
    private $BinaryValue;

    /**
     * Not implemented. Reserved for future use.
     */
    private $StringListValues = [];

    /**
     * Not implemented. Reserved for future use.
     */
    private $BinaryListValues = [];

    /**
     * Amazon SQS supports the following logical data types: `String`, `Number`, and `Binary`. For the `Number` data type,
     * you must use `StringValue`.
     */
    private $DataType;

    /**
     * @param array{
     *   StringValue?: string,
     *   BinaryValue?: string,
     *   StringListValues?: string[],
     *   BinaryListValues?: string[],
     *   DataType: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->StringValue = $input['StringValue'] ?? null;
        $this->BinaryValue = $input['BinaryValue'] ?? null;
        $this->StringListValues = $input['StringListValues'] ?? [];
        $this->BinaryListValues = $input['BinaryListValues'] ?? [];
        $this->DataType = $input['DataType'] ?? null;
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

    public function getBinaryValue(): ?string
    {
        return $this->BinaryValue;
    }

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

    public function getStringValue(): ?string
    {
        return $this->StringValue;
    }
}
