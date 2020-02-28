<?php

namespace AsyncAws\Sns\Input;

use AsyncAws\Core\Exception\InvalidArgument;

class MessageAttributeValue
{
    /**
     * Amazon SNS supports the following logical data types: String, String.Array, Number, and Binary. For more information,
     * see Message Attribute Data Types.
     *
     * @see https://docs.aws.amazon.com/sns/latest/dg/SNSMessageAttributes.html#SNSMessageAttributes.DataTypes
     * @required
     *
     * @var string|null
     */
    private $DataType;

    /**
     * Strings are Unicode with UTF8 binary encoding. For a list of code values, see ASCII Printable Characters.
     *
     * @see https://en.wikipedia.org/wiki/ASCII#ASCII_printable_characters
     *
     * @var string|null
     */
    private $StringValue;

    /**
     * Binary type attributes can store any binary data, for example, compressed data, encrypted data, or images.
     *
     * @var string|null
     */
    private $BinaryValue;

    /**
     * @param array{
     *   DataType: string,
     *   StringValue?: string,
     *   BinaryValue?: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->DataType = $input['DataType'] ?? null;
        $this->StringValue = $input['StringValue'] ?? null;
        $this->BinaryValue = $input['BinaryValue'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getBinaryValue(): ?string
    {
        return $this->BinaryValue;
    }

    public function getDataType(): ?string
    {
        return $this->DataType;
    }

    public function getStringValue(): ?string
    {
        return $this->StringValue;
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
