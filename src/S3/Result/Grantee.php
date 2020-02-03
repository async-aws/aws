<?php

namespace AsyncAws\S3\Result;

class Grantee
{
    /**
     * Screen name of the grantee.
     */
    private $DisplayName;

    /**
     * Email address of the grantee.
     */
    private $EmailAddress;

    /**
     * The canonical user ID of the grantee.
     */
    private $ID;

    /**
     * Type of grantee.
     */
    private $Type;

    /**
     * URI of the grantee group.
     */
    private $URI;

    /**
     * @param array{
     *   DisplayName?: string,
     *   EmailAddress?: string,
     *   ID?: string,
     *   Type: string,
     *   URI?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->DisplayName = $input['DisplayName'] ?? null;
        $this->EmailAddress = $input['EmailAddress'] ?? null;
        $this->ID = $input['ID'] ?? null;
        $this->Type = $input['Type'] ?? null;
        $this->URI = $input['URI'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getDisplayName(): ?string
    {
        return $this->DisplayName;
    }

    public function getEmailAddress(): ?string
    {
        return $this->EmailAddress;
    }

    public function getID(): ?string
    {
        return $this->ID;
    }

    public function getType(): string
    {
        return $this->Type;
    }

    public function getURI(): ?string
    {
        return $this->URI;
    }
}
