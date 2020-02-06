<?php

namespace AsyncAws\Ses\Input;

class EmailContent
{
    /**
     * The simple email message. The message consists of a subject and a message body.
     *
     * @var Message|null
     */
    private $Simple;

    /**
     * The raw email message. The message has to meet the following criteria:.
     *
     * @var RawMessage|null
     */
    private $Raw;

    /**
     * The template to use for the email message.
     *
     * @var Template|null
     */
    private $Template;

    /**
     * @param array{
     *   Simple?: \AsyncAws\Ses\Input\Message|array,
     *   Raw?: \AsyncAws\Ses\Input\RawMessage|array,
     *   Template?: \AsyncAws\Ses\Input\Template|array,
     * } $input
     */
    public function __construct(array $input = [])
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

    public function setRaw(?RawMessage $value): self
    {
        $this->Raw = $value;

        return $this;
    }

    public function setSimple(?Message $value): self
    {
        $this->Simple = $value;

        return $this;
    }

    public function setTemplate(?Template $value): self
    {
        $this->Template = $value;

        return $this;
    }

    public function validate(): void
    {
        if ($this->Simple) {
            $this->Simple->validate();
        }
        if ($this->Raw) {
            $this->Raw->validate();
        }
        if ($this->Template) {
            $this->Template->validate();
        }
    }
}
