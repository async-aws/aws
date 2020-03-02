<?php

namespace AsyncAws\S3\Result;

class Owner
{
    private $DisplayName;

    private $ID;

    /**
     * @param array{
     *   DisplayName: ?string,
     *   ID: ?string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->DisplayName = $input['DisplayName'];
        $this->ID = $input['ID'];
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * Container for the display name of the owner.
     */
    public function getDisplayName(): ?string
    {
        return $this->DisplayName;
    }

    /**
     * Container for the ID of the owner.
     */
    public function getID(): ?string
    {
        return $this->ID;
    }
}
