<?php

namespace AsyncAws\Ses\Input;

class Template
{
    /**
     * The Amazon Resource Name (ARN) of the template.
     *
     * @var string|null
     */
    private $TemplateArn;

    /**
     * An object that defines the values to use for message variables in the template. This object is a set of key-value
     * pairs. Each key defines a message variable in the template. The corresponding value defines the value to use for that
     * variable.
     *
     * @var string|null
     */
    private $TemplateData;

    /**
     * @param array{
     *   TemplateArn?: string,
     *   TemplateData?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
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

    public function setTemplateArn(?string $value): self
    {
        $this->TemplateArn = $value;

        return $this;
    }

    public function setTemplateData(?string $value): self
    {
        $this->TemplateData = $value;

        return $this;
    }

    public function validate(): void
    {
        // There are no required properties
    }
}
