<?php

namespace AsyncAws\Ses\Input;

use AsyncAws\Core\Exception\InvalidArgument;

class MessageTag
{
    /**
     * The name of the message tag. The message tag name has to meet the following criteria:.
     *
     * @required
     *
     * @var string|null
     */
    private $Name;

    /**
     * The value of the message tag. The message tag value has to meet the following criteria:.
     *
     * @required
     *
     * @var string|null
     */
    private $Value;

    /**
     * @param array{
     *   Name: string,
     *   Value: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Name = $input['Name'] ?? null;
        $this->Value = $input['Value'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function getValue(): ?string
    {
        return $this->Value;
    }

    public function setName(?string $value): self
    {
        $this->Name = $value;

        return $this;
    }

    public function setValue(?string $value): self
    {
        $this->Value = $value;

        return $this;
    }

    public function validate(): void
    {
        if (null === $this->Name) {
            throw new InvalidArgument(sprintf('Missing parameter "Name" when validating the "%s". The value cannot be null.', __CLASS__));
        }

        if (null === $this->Value) {
            throw new InvalidArgument(sprintf('Missing parameter "Value" when validating the "%s". The value cannot be null.', __CLASS__));
        }
    }
}
