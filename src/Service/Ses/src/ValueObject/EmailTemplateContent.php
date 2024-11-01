<?php

namespace AsyncAws\Ses\ValueObject;

/**
 * The content of the email, composed of a subject line, an HTML part, and a text-only part.
 */
final class EmailTemplateContent
{
    /**
     * The subject line of the email.
     *
     * @var string|null
     */
    private $subject;

    /**
     * The email body that will be visible to recipients whose email clients do not display HTML.
     *
     * @var string|null
     */
    private $text;

    /**
     * The HTML body of the email.
     *
     * @var string|null
     */
    private $html;

    /**
     * @param array{
     *   Subject?: null|string,
     *   Text?: null|string,
     *   Html?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->subject = $input['Subject'] ?? null;
        $this->text = $input['Text'] ?? null;
        $this->html = $input['Html'] ?? null;
    }

    /**
     * @param array{
     *   Subject?: null|string,
     *   Text?: null|string,
     *   Html?: null|string,
     * }|EmailTemplateContent $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getHtml(): ?string
    {
        return $this->html;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->subject) {
            $payload['Subject'] = $v;
        }
        if (null !== $v = $this->text) {
            $payload['Text'] = $v;
        }
        if (null !== $v = $this->html) {
            $payload['Html'] = $v;
        }

        return $payload;
    }
}
