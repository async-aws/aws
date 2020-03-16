<?php

namespace AsyncAws\S3\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\S3\Enum\Type;

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
     *   DisplayName?: null|string,
     *   EmailAddress?: null|string,
     *   ID?: null|string,
     *   Type: \AsyncAws\S3\Enum\Type::*,
     *   URI?: null|string,
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
     * @return Type::*
     */
    public function getType(): string
    {
        return $this->Type;
    }

    public function getURI(): ?string
    {
        return $this->URI;
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
