<?php

namespace AsyncAws\S3\Input;

use AsyncAws\Core\Exception\InvalidArgument;

class Grantee
{
    /**
     * Screen name of the grantee.
     *
     * @var string|null
     */
    private $DisplayName;

    /**
     * Email address of the grantee.
     *
     * @var string|null
     */
    private $EmailAddress;

    /**
     * The canonical user ID of the grantee.
     *
     * @var string|null
     */
    private $ID;

    /**
     * Type of grantee.
     *
     * @required
     *
     * @var string|null
     */
    private $Type;

    /**
     * URI of the grantee group.
     *
     * @var string|null
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

    public function getType(): ?string
    {
        return $this->Type;
    }

    public function getURI(): ?string
    {
        return $this->URI;
    }

    public function setDisplayName(?string $value): self
    {
        $this->DisplayName = $value;

        return $this;
    }

    public function setEmailAddress(?string $value): self
    {
        $this->EmailAddress = $value;

        return $this;
    }

    public function setID(?string $value): self
    {
        $this->ID = $value;

        return $this;
    }

    public function setType(?string $value): self
    {
        $this->Type = $value;

        return $this;
    }

    public function setURI(?string $value): self
    {
        $this->URI = $value;

        return $this;
    }

    public function validate(): void
    {
        foreach (['Type'] as $name) {
            if (null === $this->$name) {
                throw new InvalidArgument(sprintf('Missing parameter "%s" when validating the "%s". The value cannot be null.', $name, __CLASS__));
            }
        }
    }
}
