<?php

namespace AsyncAws\Ses\Input;

class Body
{
    /**
     * An object that represents the version of the message that is displayed in email clients that don't support HTML, or
     * clients where the recipient has disabled HTML rendering.
     *
     * @var Content|null
     */
    private $Text;

    /**
     * An object that represents the version of the message that is displayed in email clients that support HTML. HTML
     * messages can include formatted text, hyperlinks, images, and more.
     *
     * @var Content|null
     */
    private $Html;

    /**
     * @param array{
     *   Text?: \AsyncAws\Ses\Input\Content|array,
     *   Html?: \AsyncAws\Ses\Input\Content|array,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->Text = isset($input['Text']) ? Content::create($input['Text']) : null;
        $this->Html = isset($input['Html']) ? Content::create($input['Html']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getHtml(): ?Content
    {
        return $this->Html;
    }

    public function getText(): ?Content
    {
        return $this->Text;
    }

    public function setHtml(?Content $value): self
    {
        $this->Html = $value;

        return $this;
    }

    public function setText(?Content $value): self
    {
        $this->Text = $value;

        return $this;
    }

    public function validate(): void
    {
        if (null !== $this->Text) {
            $this->Text->validate();
        }

        if (null !== $this->Html) {
            $this->Html->validate();
        }
    }
}
