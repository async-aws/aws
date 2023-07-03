<?php

namespace AsyncAws\Ses\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Represents the raw content of an email message.
 */
final class RawMessage
{
    /**
     * The raw email message. The message has to meet the following criteria:.
     *
     * - The message has to contain a header and a body, separated by one blank line.
     * - All of the required header fields must be present in the message.
     * - Each part of a multipart MIME message must be formatted properly.
     * - Attachments must be in a file format that the Amazon SES supports.
     * - The entire message must be Base64 encoded.
     * - If any of the MIME parts in your message contain content that is outside of the 7-bit ASCII character range, you
     *   should encode that content to ensure that recipients' email clients render the message properly.
     * - The length of any single line of text in the message can't exceed 1,000 characters. This restriction is defined in
     *   RFC 5321 [^1].
     *
     * [^1]: https://tools.ietf.org/html/rfc5321
     *
     * @var string
     */
    private $data;

    /**
     * @param array{
     *   Data: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->data = $input['Data'] ?? $this->throwException(new InvalidArgument('Missing required field "Data".'));
    }

    /**
     * @param array{
     *   Data: string,
     * }|RawMessage $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getData(): string
    {
        return $this->data;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->data;
        $payload['Data'] = base64_encode($v);

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
