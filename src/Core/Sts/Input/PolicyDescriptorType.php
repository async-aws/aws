<?php

namespace AsyncAws\Core\Sts\Input;

class PolicyDescriptorType
{
    /**
     * @var string|null
     */
    private $arn;

    /**
     * @param array{
     *   arn?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->arn = $input['arn'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getarn(): ?string
    {
        return $this->arn;
    }

    public function setarn(?string $value): self
    {
        $this->arn = $value;

        return $this;
    }

    public function validate(): void
    {
        // There are no required properties
    }
}
