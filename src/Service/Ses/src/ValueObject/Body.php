<?php

namespace AsyncAws\Ses\ValueObject;

/**
 * The body of the message. You can specify an HTML version of the message, a text-only version of the message, or both.
 */
final class Body
{
    /**
     * An object that represents the version of the message that is displayed in email clients that don't support HTML, or
     * clients where the recipient has disabled HTML rendering.
     */
    private $Text;

    /**
     * An object that represents the version of the message that is displayed in email clients that support HTML. HTML
     * messages can include formatted text, hyperlinks, images, and more.
     */
    private $Html;

    /**
     * @param array{
     *   Text?: null|Content|array,
     *   Html?: null|Content|array,
     * } $input
     */
    public function __construct(array $input)
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

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->Text) {
            $payload['Text'] = $v->requestBody();
        }
        if (null !== $v = $this->Html) {
            $payload['Html'] = $v->requestBody();
        }

        return $payload;
    }
}
