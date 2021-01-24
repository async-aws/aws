<?php

namespace AsyncAws\Ses\ValueObject;

/**
 * The template to use for the email message.
 */
final class Template
{
    /**
     * The name of the template. You will refer to this name when you send email using the `SendTemplatedEmail` or
     * `SendBulkTemplatedEmail` operations.
     */
    private $TemplateName;

    /**
     * The Amazon Resource Name (ARN) of the template.
     */
    private $TemplateArn;

    /**
     * An object that defines the values to use for message variables in the template. This object is a set of key-value
     * pairs. Each key defines a message variable in the template. The corresponding value defines the value to use for that
     * variable.
     */
    private $TemplateData;

    /**
     * @param array{
     *   TemplateName?: null|string,
     *   TemplateArn?: null|string,
     *   TemplateData?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->TemplateName = $input['TemplateName'] ?? null;
        $this->TemplateArn = $input['TemplateArn'] ?? null;
        $this->TemplateData = $input['TemplateData'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getTemplateArn(): ?string
    {
        return $this->TemplateArn;
    }

    public function getTemplateData(): ?string
    {
        return $this->TemplateData;
    }

    public function getTemplateName(): ?string
    {
        return $this->TemplateName;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->TemplateName) {
            $payload['TemplateName'] = $v;
        }
        if (null !== $v = $this->TemplateArn) {
            $payload['TemplateArn'] = $v;
        }
        if (null !== $v = $this->TemplateData) {
            $payload['TemplateData'] = $v;
        }

        return $payload;
    }
}
