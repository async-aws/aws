<?php

namespace AsyncAws\Sqs\Input;

use AsyncAws\Core\Exception\InvalidArgument;

class MessageAttributeValue
{
    /**
     * Strings are Unicode with UTF-8 binary encoding. For a list of code values, see ASCII Printable Characters.
     *
     * @see http://en.wikipedia.org/wiki/ASCII#ASCII_printable_characters
     *
     * @var string|null
     */
    private $StringValue;

    /**
     * Binary type attributes can store any binary data, such as compressed data, encrypted data, or images.
     *
     * @var string|null
     */
    private $BinaryValue;

    /**
     * Not implemented. Reserved for future use.
     *
     * @var string[]
     */
    private $StringListValues;

    /**
     * Not implemented. Reserved for future use.
     *
     * @var string[]
     */
    private $BinaryListValues;

    /**
     * Amazon SQS supports the following logical data types: `String`, `Number`, and `Binary`. For the `Number` data type,
     * you must use `StringValue`.
     *
     * @required
     *
     * @var string|null
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
    public function __construct(array $input)
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

    public function getBinaryListValues(): array
    {
        return $this->BinaryListValues;
    }

    public function getBinaryValue(): ?string
    {
        return $this->BinaryValue;
    }

    public function getDataType(): ?string
    {
        return $this->DataType;
    }

    public function getStringListValues(): array
    {
        return $this->StringListValues;
    }

    public function getStringValue(): ?string
    {
        return $this->StringValue;
    }

    public function setBinaryListValues(array $value): self
    {
        $this->BinaryListValues = $value;

        return $this;
    }

    public function setBinaryValue(?string $value): self
    {
        $this->BinaryValue = $value;

        return $this;
    }

    public function setDataType(?string $value): self
    {
        $this->DataType = $value;

        return $this;
    }

    public function setStringListValues(array $value): self
    {
        $this->StringListValues = $value;

        return $this;
    }

    public function setStringValue(?string $value): self
    {
        $this->StringValue = $value;

        return $this;
    }

    public function validate(): void
    {
        foreach (['DataType'] as $name) {
            if (null === $this->$name) {
                throw new InvalidArgument(sprintf('Missing parameter "%s" when validating the "%s". The value cannot be null.', $name, __CLASS__));
            }
        }
    }
}
