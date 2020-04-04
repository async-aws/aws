<?php

namespace AsyncAws\Ses\ValueObject;

final class EmailContent
{
    /**
     * The simple email message. The message consists of a subject and a message body.
     */
    private $Simple;

    /**
     * The raw email message. The message has to meet the following criteria:.
     */
    private $Raw;

    /**
     * The template to use for the email message.
     */
    private $Template;

    /**
     * @param array{
     *   Simple?: null|\AsyncAws\Ses\ValueObject\Message|array,
     *   Raw?: null|\AsyncAws\Ses\ValueObject\RawMessage|array,
     *   Template?: null|\AsyncAws\Ses\ValueObject\Template|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Simple = isset($input['Simple']) ? Message::create($input['Simple']) : null;
        $this->Raw = isset($input['Raw']) ? RawMessage::create($input['Raw']) : null;
        $this->Template = isset($input['Template']) ? Template::create($input['Template']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getRaw(): ?RawMessage
    {
        return $this->Raw;
    }

    public function getSimple(): ?Message
    {
        return $this->Simple;
    }

    public function getTemplate(): ?Template
    {
        return $this->Template;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->Simple) {
            $payload['Simple'] = $v->requestBody();
        }
        if (null !== $v = $this->Raw) {
            $payload['Raw'] = $v->requestBody();
        }
        if (null !== $v = $this->Template) {
            $payload['Template'] = $v->requestBody();
        }

        return $payload;
    }
}
