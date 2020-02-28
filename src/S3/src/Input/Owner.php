<?php

namespace AsyncAws\S3\Input;

class Owner
{
    /**
     * Container for the display name of the owner.
     *
     * @var string|null
     */
    private $DisplayName;

    /**
     * Container for the ID of the owner.
     *
     * @var string|null
     */
    private $ID;

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

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDisplayName(): ?string
    {
        return $this->DisplayName;
    }

    public function getID(): ?string
    {
        return $this->ID;
    }

    public function setDisplayName(?string $value): self
    {
        $this->DisplayName = $value;

        return $this;
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
