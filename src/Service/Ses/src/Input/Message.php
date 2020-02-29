<?php

namespace AsyncAws\Ses\Input;

use AsyncAws\Core\Exception\InvalidArgument;

class Message
{
    /**
     * The subject line of the email. The subject line can only contain 7-bit ASCII characters. However, you can specify
     * non-ASCII characters in the subject line by using encoded-word syntax, as described in RFC 2047.
     *
     * @see https://tools.ietf.org/html/rfc2047
     * @required
     *
     * @var Content|null
     */
    private $Subject;

    /**
     * The body of the message. You can specify an HTML version of the message, a text-only version of the message, or both.
     *
     * @required
     *
     * @var Body|null
     */
    private $Body;

    /**
     * @param array{
     *   Subject: \AsyncAws\Ses\Input\Content|array,
     *   Body: \AsyncAws\Ses\Input\Body|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Subject = isset($input['Subject']) ? Content::create($input['Subject']) : null;
        $this->Body = isset($input['Body']) ? Body::create($input['Body']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getBody(): ?Body
    {
        return $this->Body;
    }

    public function getSubject(): ?Content
    {
        return $this->Subject;
    }

    public function setBody(?Body $value): self
    {
        $this->Body = $value;

        return $this;
    }

    public function setSubject(?Content $value): self
    {
        $this->Subject = $value;

        return $this;
    }

    public function validate(): void
    {
        foreach (['Subject', 'Body'] as $name) {
            if (null === $this->$name) {
                throw new InvalidArgument(sprintf('Missing parameter "%s" when validating the "%s". The value cannot be null.', $name, __CLASS__));
            }
        }
        if ($this->Subject) {
            $this->Subject->validate();
        }
        if ($this->Body) {
            $this->Body->validate();
        }
    }
}
