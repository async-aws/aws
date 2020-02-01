<?php

namespace AsyncAws\S3\Input;

use AsyncAws\Core\Exception\InvalidArgument;

class Owner
{
    /**
     * @var string|null
     */
    private $DisplayName;

    /**
     * @var string|null
     */
    private $ID;

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @param array{
     *   DisplayName?: string,
     *   ID?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->DisplayName = $input["DisplayName"] ?? null;
        $this->ID = $input["ID"] ?? null;
    }

    public function getDisplayName(): ?string
    {
        return $this->DisplayName;
    }

    public function setDisplayName(?string $value): self
    {
        $this->DisplayName = $value;

        return $this;
    }

    public function getID(): ?string
    {
        return $this->ID;
    }

    public function setID(?string $value): self
    {
        $this->ID = $value;

        return $this;
    }

    public function validate(): void
    {
        foreach ([''] as $name) {
            if (null === $value = $this->$name) {
                throw new InvalidArgument(sprintf('Missing parameter "%s" when validating the "%s". The value cannot be null.', $name, __CLASS__));
            }

            if (\is_object($value) && method_exists($value, 'validate')) {
                $value->validate();
            }
        }
    }
}
