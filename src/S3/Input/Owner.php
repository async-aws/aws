<?php

namespace AsyncAws\S3\Input;

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
        $this->DisplayName = $input['DisplayName'] ?? null;
        $this->ID = $input['ID'] ?? null;
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
        // There are no required properties
    }
}
