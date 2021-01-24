<?php

namespace AsyncAws\Sqs\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * The user-specified message system attribute value. For string data types, the `Value` attribute has the same
 * restrictions on the content as the message body. For more information, see `SendMessage.`
 * `Name`, `type`, `value` and the message body must not be empty or null.
 */
final class MessageSystemAttributeValue
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
    private $StringListValues;

    /**
     * Not implemented. Reserved for future use.
     */
    private $BinaryListValues;

    /**
     * Amazon SQS supports the following logical data types: `String`, `Number`, and `Binary`. For the `Number` data type,
     * you must use `StringValue`.
     */
    private $DataType;

    /**
     * @param array{
     *   StringValue?: null|string,
     *   BinaryValue?: null|string,
     *   StringListValues?: null|string[],
     *   BinaryListValues?: null|string[],
     *   DataType: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->StringValue = $input['StringValue'] ?? null;
        $this->BinaryValue = $input['BinaryValue'] ?? null;
        $this->StringListValues = $input['StringListValues'] ?? null;
        $this->BinaryListValues = $input['BinaryListValues'] ?? null;
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
        return $this->BinaryListValues ?? [];
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
        return $this->StringListValues ?? [];
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
        if (null !== $v = $this->StringValue) {
            $payload['StringValue'] = $v;
        }
        if (null !== $v = $this->BinaryValue) {
            $payload['BinaryValue'] = base64_encode($v);
        }
        if (null !== $v = $this->StringListValues) {
            $index = 0;
            foreach ($v as $mapValue) {
                ++$index;
                $payload["StringListValue.$index"] = $mapValue;
            }
        }
        if (null !== $v = $this->BinaryListValues) {
            $index = 0;
            foreach ($v as $mapValue) {
                ++$index;
                $payload["BinaryListValue.$index"] = base64_encode($mapValue);
            }
        }
        if (null === $v = $this->DataType) {
            throw new InvalidArgument(sprintf('Missing parameter "DataType" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['DataType'] = $v;

        return $payload;
    }
}
