<?php

namespace AsyncAws\Ses\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Contains the name and value of a message header that you add to an email.
 */
final class MessageHeader
{
    /**
     * The name of the message header. The message header name has to meet the following criteria:
     *
     * - Can contain any printable ASCII character (33 - 126) except for colon (:).
     * - Can contain no more than 126 characters.
     *
     * @var string
     */
    private $name;

    /**
     * The value of the message header. The message header value has to meet the following criteria:
     *
     * - Can contain any printable ASCII character.
     * - Can contain no more than 870 characters.
     *
     * @var string
     */
    private $value;

    /**
     * @param array{
     *   Name: string,
     *   Value: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['Name'] ?? $this->throwException(new InvalidArgument('Missing required field "Name".'));
        $this->value = $input['Value'] ?? $this->throwException(new InvalidArgument('Missing required field "Value".'));
    }

    /**
     * @param array{
     *   Name: string,
     *   Value: string,
     * }|MessageHeader $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->name;
        $payload['Name'] = $v;
        $v = $this->value;
        $payload['Value'] = $v;

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
