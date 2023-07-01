<?php

namespace AsyncAws\Ses\ValueObject;

/**
 * An object that defines the email template to use for an email message, and the values to use for any message
 * variables in that template. An *email template* is a type of message template that contains content that you want to
 * define, save, and reuse in email messages that you send.
 */
final class Template
{
    /**
     * The name of the template. You will refer to this name when you send email using the `SendTemplatedEmail` or
     * `SendBulkTemplatedEmail` operations.
     *
     * @var string|null
     */
    private $templateName;

    /**
     * The Amazon Resource Name (ARN) of the template.
     *
     * @var string|null
     */
    private $templateArn;

    /**
     * An object that defines the values to use for message variables in the template. This object is a set of key-value
     * pairs. Each key defines a message variable in the template. The corresponding value defines the value to use for that
     * variable.
     *
     * @var string|null
     */
    private $templateData;

    /**
     * @param array{
     *   TemplateName?: null|string,
     *   TemplateArn?: null|string,
     *   TemplateData?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->templateName = $input['TemplateName'] ?? null;
        $this->templateArn = $input['TemplateArn'] ?? null;
        $this->templateData = $input['TemplateData'] ?? null;
    }

    /**
     * @param array{
     *   TemplateName?: null|string,
     *   TemplateArn?: null|string,
     *   TemplateData?: null|string,
     * }|Template $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getTemplateArn(): ?string
    {
        return $this->templateArn;
    }

    public function getTemplateData(): ?string
    {
        return $this->templateData;
    }

    public function getTemplateName(): ?string
    {
        return $this->templateName;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->templateName) {
            $payload['TemplateName'] = $v;
        }
        if (null !== $v = $this->templateArn) {
            $payload['TemplateArn'] = $v;
        }
        if (null !== $v = $this->templateData) {
            $payload['TemplateData'] = $v;
        }

        return $payload;
    }
}
