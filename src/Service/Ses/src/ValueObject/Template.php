<?php

namespace AsyncAws\Ses\ValueObject;

/**
 * An object that defines the email template to use for an email message, and the values to use for any message
 * variables in that template. An *email template* is a type of message template that contains content that you want to
 * reuse in email messages that you send. You can specifiy the email template by providing the name or ARN of an *email
 * template* previously saved in your Amazon SES account or by providing the full template content.
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
     * The content of the template.
     *
     * > Amazon SES supports only simple substitions when you send email using the `SendEmail` or `SendBulkEmail` operations
     * > and you provide the full template content in the request.
     *
     * @var EmailTemplateContent|null
     */
    private $templateContent;

    /**
     * An object that defines the values to use for message variables in the template. This object is a set of key-value
     * pairs. Each key defines a message variable in the template. The corresponding value defines the value to use for that
     * variable.
     *
     * @var string|null
     */
    private $templateData;

    /**
     * The list of message headers that will be added to the email message.
     *
     * @var MessageHeader[]|null
     */
    private $headers;

    /**
     * @param array{
     *   TemplateName?: null|string,
     *   TemplateArn?: null|string,
     *   TemplateContent?: null|EmailTemplateContent|array,
     *   TemplateData?: null|string,
     *   Headers?: null|array<MessageHeader|array>,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->templateName = $input['TemplateName'] ?? null;
        $this->templateArn = $input['TemplateArn'] ?? null;
        $this->templateContent = isset($input['TemplateContent']) ? EmailTemplateContent::create($input['TemplateContent']) : null;
        $this->templateData = $input['TemplateData'] ?? null;
        $this->headers = isset($input['Headers']) ? array_map([MessageHeader::class, 'create'], $input['Headers']) : null;
    }

    /**
     * @param array{
     *   TemplateName?: null|string,
     *   TemplateArn?: null|string,
     *   TemplateContent?: null|EmailTemplateContent|array,
     *   TemplateData?: null|string,
     *   Headers?: null|array<MessageHeader|array>,
     * }|Template $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return MessageHeader[]
     */
    public function getHeaders(): array
    {
        return $this->headers ?? [];
    }

    public function getTemplateArn(): ?string
    {
        return $this->templateArn;
    }

    public function getTemplateContent(): ?EmailTemplateContent
    {
        return $this->templateContent;
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
        if (null !== $v = $this->templateContent) {
            $payload['TemplateContent'] = $v->requestBody();
        }
        if (null !== $v = $this->templateData) {
            $payload['TemplateData'] = $v;
        }
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
}
