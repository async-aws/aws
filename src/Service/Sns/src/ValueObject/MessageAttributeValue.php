<?php

namespace AsyncAws\Sns\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * The user-specified message attribute value. For string data types, the value attribute has the same restrictions on
 * the content as the message body. For more information, see Publish [^1].
 *
 * Name, type, and value must not be empty or null. In addition, the message body should not be empty or null. All parts
 * of the message attribute, including name, type, and value, are included in the message size restriction, which is
 * currently 256 KB (262,144 bytes). For more information, see Amazon SNS message attributes [^2] and Publishing to a
 * mobile phone [^3] in the *Amazon SNS Developer Guide.*
 *
 * [^1]: https://docs.aws.amazon.com/sns/latest/api/API_Publish.html
 * [^2]: https://docs.aws.amazon.com/sns/latest/dg/SNSMessageAttributes.html
 * [^3]: https://docs.aws.amazon.com/sns/latest/dg/sms_publish-to-phone.html
 */
final class MessageAttributeValue
{
    /**
     * Amazon SNS supports the following logical data types: String, String.Array, Number, and Binary. For more information,
     * see Message Attribute Data Types [^1].
     *
     * [^1]: https://docs.aws.amazon.com/sns/latest/dg/SNSMessageAttributes.html#SNSMessageAttributes.DataTypes
     *
     * @var string
     */
    private $dataType;

    /**
     * Strings are Unicode with UTF8 binary encoding. For a list of code values, see ASCII Printable Characters [^1].
     *
     * [^1]: https://en.wikipedia.org/wiki/ASCII#ASCII_printable_characters
     *
     * @var string|null
     */
    private $stringValue;

    /**
     * Binary type attributes can store any binary data, for example, compressed data, encrypted data, or images.
     *
     * @var string|null
     */
    private $binaryValue;

    /**
     * @param array{
     *   DataType: string,
     *   StringValue?: null|string,
     *   BinaryValue?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->dataType = $input['DataType'] ?? $this->throwException(new InvalidArgument('Missing required field "DataType".'));
        $this->stringValue = $input['StringValue'] ?? null;
        $this->binaryValue = $input['BinaryValue'] ?? null;
    }

    /**
     * @param array{
     *   DataType: string,
     *   StringValue?: null|string,
     *   BinaryValue?: null|string,
     * }|MessageAttributeValue $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getBinaryValue(): ?string
    {
        return $this->binaryValue;
    }

    public function getDataType(): string
    {
        return $this->dataType;
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
        $v = $this->dataType;
        $payload['DataType'] = $v;
        if (null !== $v = $this->stringValue) {
            $payload['StringValue'] = $v;
        }
        if (null !== $v = $this->binaryValue) {
            $payload['BinaryValue'] = base64_encode($v);
        }

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
