<?php

namespace AsyncAws\Sqs\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * The user-specified message system attribute value. For string data types, the `Value` attribute has the same
 * restrictions on the content as the message body. For more information, see `SendMessage.`.
 *
 * `Name`, `type`, `value` and the message body must not be empty or null.
 */
final class MessageSystemAttributeValue
{
    /**
     * Strings are Unicode with UTF-8 binary encoding. For a list of code values, see ASCII Printable Characters [^1].
     *
     * [^1]: http://en.wikipedia.org/wiki/ASCII#ASCII_printable_characters
     *
     * @var string|null
     */
    private $stringValue;

    /**
     * Binary type attributes can store any binary data, such as compressed data, encrypted data, or images.
     *
     * @var string|null
     */
    private $binaryValue;

    /**
     * Not implemented. Reserved for future use.
     *
     * @var string[]|null
     */
    private $stringListValues;

    /**
     * Not implemented. Reserved for future use.
     *
     * @var string[]|null
     */
    private $binaryListValues;

    /**
     * Amazon SQS supports the following logical data types: `String`, `Number`, and `Binary`. For the `Number` data type,
     * you must use `StringValue`.
     *
     * You can also append custom labels. For more information, see Amazon SQS Message Attributes [^1] in the *Amazon SQS
     * Developer Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/AWSSimpleQueueService/latest/SQSDeveloperGuide/sqs-message-metadata.html#sqs-message-attributes
     *
     * @var string
     */
    private $dataType;

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
        $this->stringValue = $input['StringValue'] ?? null;
        $this->binaryValue = $input['BinaryValue'] ?? null;
        $this->stringListValues = $input['StringListValues'] ?? null;
        $this->binaryListValues = $input['BinaryListValues'] ?? null;
        $this->dataType = $input['DataType'] ?? $this->throwException(new InvalidArgument('Missing required field "DataType".'));
    }

    /**
     * @param array{
     *   StringValue?: null|string,
     *   BinaryValue?: null|string,
     *   StringListValues?: null|string[],
     *   BinaryListValues?: null|string[],
     *   DataType: string,
     * }|MessageSystemAttributeValue $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return string[]
     */
    public function getBinaryListValues(): array
    {
        return $this->binaryListValues ?? [];
    }

    public function getBinaryValue(): ?string
    {
        return $this->binaryValue;
    }

    public function getDataType(): string
    {
        return $this->dataType;
    }

    /**
     * @return string[]
     */
    public function getStringListValues(): array
    {
        return $this->stringListValues ?? [];
    }

    public function getStringValue(): ?string
    {
        return $this->stringValue;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->stringValue) {
            $payload['StringValue'] = $v;
        }
        if (null !== $v = $this->binaryValue) {
            $payload['BinaryValue'] = base64_encode($v);
        }
        if (null !== $v = $this->stringListValues) {
            $index = -1;
            $payload['StringListValues'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['StringListValues'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->binaryListValues) {
            $index = -1;
            $payload['BinaryListValues'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['BinaryListValues'][$index] = base64_encode($listValue);
            }
        }
        $v = $this->dataType;
        $payload['DataType'] = $v;

        return $payload;
    }

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
