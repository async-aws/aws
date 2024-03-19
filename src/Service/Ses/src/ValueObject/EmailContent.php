<?php

namespace AsyncAws\Ses\ValueObject;

/**
 * An object that defines the entire content of the email, including the message headers and the body content. You can
 * create a simple email message, in which you specify the subject and the text and HTML versions of the message body.
 * You can also create raw messages, in which you specify a complete MIME-formatted message. Raw messages can include
 * attachments and custom headers.
 */
final class EmailContent
{
    /**
     * The simple email message. The message consists of a subject and a message body.
     *
     * @var Message|null
     */
    private $simple;

    /**
     * The raw email message. The message has to meet the following criteria:
     *
     * - The message has to contain a header and a body, separated by one blank line.
     * - All of the required header fields must be present in the message.
     * - Each part of a multipart MIME message must be formatted properly.
     * - If you include attachments, they must be in a file format that the Amazon SES API v2 supports.
     * - The raw data of the message needs to base64-encoded if you are accessing Amazon SES directly through the HTTPS
     *   interface. If you are accessing Amazon SES using an Amazon Web Services SDK, the SDK takes care of the base
     *   64-encoding for you.
     * - If any of the MIME parts in your message contain content that is outside of the 7-bit ASCII character range, you
     *   should encode that content to ensure that recipients' email clients render the message properly.
     * - The length of any single line of text in the message can't exceed 1,000 characters. This restriction is defined in
     *   RFC 5321 [^1].
     *
     * [^1]: https://tools.ietf.org/html/rfc5321
     *
     * @var RawMessage|null
     */
    private $raw;

    /**
     * The template to use for the email message.
     *
     * @var Template|null
     */
    private $template;

    /**
     * @param array{
     *   Simple?: null|Message|array,
     *   Raw?: null|RawMessage|array,
     *   Template?: null|Template|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->simple = isset($input['Simple']) ? Message::create($input['Simple']) : null;
        $this->raw = isset($input['Raw']) ? RawMessage::create($input['Raw']) : null;
        $this->template = isset($input['Template']) ? Template::create($input['Template']) : null;
    }

    /**
     * @param array{
     *   Simple?: null|Message|array,
     *   Raw?: null|RawMessage|array,
     *   Template?: null|Template|array,
     * }|EmailContent $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getRaw(): ?RawMessage
    {
        return $this->raw;
    }

    public function getSimple(): ?Message
    {
        return $this->simple;
    }

    public function getTemplate(): ?Template
    {
        return $this->template;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->simple) {
            $payload['Simple'] = $v->requestBody();
        }
        if (null !== $v = $this->raw) {
            $payload['Raw'] = $v->requestBody();
        }
        if (null !== $v = $this->template) {
            $payload['Template'] = $v->requestBody();
        }

        return $payload;
    }
}
