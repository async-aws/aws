<?php

namespace AsyncAws\S3\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\S3\Enum\Type;

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
     * @var Type::*|null
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
     *   Type: \AsyncAws\S3\Enum\Type::*,
     *   URI?: string,
     * } $input
     */
    public function __construct(array $input)
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

    /**
     * @return Type::*|null
     */
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

    /**
     * @param Type::*|null $value
     */
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
        if (null === $this->Type) {
            throw new InvalidArgument(sprintf('Missing parameter "Type" when validating the "%s". The value cannot be null.', __CLASS__));
        }
        if (!Type::exists($this->Type)) {
            throw new InvalidArgument(sprintf('Invalid parameter "Type" when validating the "%s". The value "%s" is not a valid "Type".', __CLASS__, $this->Type));
        }
    }
}
