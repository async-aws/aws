<?php

namespace AsyncAws\Core\Sts\Input;

use AsyncAws\Core\Exception\InvalidArgument;

class Tag
{
    /**
     * The key for a session tag.
     *
     * @required
     *
     * @var string|null
     */
    private $Key;

    /**
     * The value for a session tag.
     *
     * @required
     *
     * @var string|null
     */
    private $Value;

    /**
     * @param array{
     *   Key: string,
     *   Value: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Key = $input['Key'] ?? null;
        $this->Value = $input['Value'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getKey(): ?string
    {
        return $this->Key;
    }

    public function getValue(): ?string
    {
        return $this->Value;
    }

    public function setKey(?string $value): self
    {
        $this->Key = $value;

        return $this;
    }

    public function setValue(?string $value): self
    {
        $this->Value = $value;

        return $this;
    }

    public function validate(): void
    {
        if (null === $this->Key) {
            throw new InvalidArgument(sprintf('Missing parameter "Key" when validating the "%s". The value cannot be null.', __CLASS__));
        }

        if (null === $this->Value) {
            throw new InvalidArgument(sprintf('Missing parameter "Value" when validating the "%s". The value cannot be null.', __CLASS__));
        }
    }
}
