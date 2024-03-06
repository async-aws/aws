<?php

namespace AsyncAws\Ses\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Represents the email message that you're sending. The `Message` object consists of a subject line and a message body.
 */
final class Message
{
    /**
     * The subject line of the email. The subject line can only contain 7-bit ASCII characters. However, you can specify
     * non-ASCII characters in the subject line by using encoded-word syntax, as described in RFC 2047 [^1].
     *
     * [^1]: https://tools.ietf.org/html/rfc2047
     *
     * @var Content
     */
    private $subject;

    /**
     * The body of the message. You can specify an HTML version of the message, a text-only version of the message, or both.
     *
     * @var Body
     */
    private $body;

    /**
     * The list of message headers that will be added to the email message.
     *
     * @var MessageHeader[]|null
     */
    private $headers;

    /**
     * @param array{
     *   Subject: Content|array,
     *   Body: Body|array,
     *   Headers?: null|array<MessageHeader|array>,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->subject = isset($input['Subject']) ? Content::create($input['Subject']) : $this->throwException(new InvalidArgument('Missing required field "Subject".'));
        $this->body = isset($input['Body']) ? Body::create($input['Body']) : $this->throwException(new InvalidArgument('Missing required field "Body".'));
        $this->headers = isset($input['Headers']) ? array_map([MessageHeader::class, 'create'], $input['Headers']) : null;
    }

    /**
     * @param array{
     *   Subject: Content|array,
     *   Body: Body|array,
     *   Headers?: null|array<MessageHeader|array>,
     * }|Message $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getBody(): Body
    {
        return $this->body;
    }

    /**
     * @return MessageHeader[]
     */
    public function getHeaders(): array
    {
        return $this->headers ?? [];
    }

    public function getSubject(): Content
    {
        return $this->subject;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        $v = $this->subject;
        $payload['Subject'] = $v->requestBody();
        $v = $this->body;
        $payload['Body'] = $v->requestBody();
        if (null !== $v = $this->headers) {
            $index = -1;
            $payload['Headers'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['Headers'][$index] = $listValue->requestBody();
            }
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
