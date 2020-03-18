<?php

namespace AsyncAws\Sns\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

class MessageAttributeValue
{
    /**
     * Amazon SNS supports the following logical data types: String, String.Array, Number, and Binary. For more information,
     * see Message Attribute Data Types.
     *
     * @see https://docs.aws.amazon.com/sns/latest/dg/SNSMessageAttributes.html#SNSMessageAttributes.DataTypes
     */
    private $DataType;

    /**
     * Strings are Unicode with UTF8 binary encoding. For a list of code values, see ASCII Printable Characters.
     *
     * @see https://en.wikipedia.org/wiki/ASCII#ASCII_printable_characters
     */
    private $StringValue;

    /**
     * Binary type attributes can store any binary data, for example, compressed data, encrypted data, or images.
     */
    private $BinaryValue;

    /**
     * @param array{
     *   DataType: string,
     *   StringValue?: null|string,
     *   BinaryValue?: null|string,
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

    public function getDataType(): string
    {
        return $this->DataType;
    }

    public function getStringValue(): ?string
    {
        return $this->StringValue;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->DataType) {
            throw new InvalidArgument(sprintf('Missing parameter "DataType" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['DataType'] = $v;
        if (null !== $v = $this->StringValue) {
            $payload['StringValue'] = $v;
        }
        if (null !== $v = $this->BinaryValue) {
            $payload['BinaryValue'] = base64_encode($v);
        }

        return $payload;
    }
}
