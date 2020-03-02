<?php

namespace AsyncAws\S3\Result;

use AsyncAws\S3\Enum\Type;

class Grantee
{
    private $DisplayName;

    private $EmailAddress;

    private $ID;

    private $Type;

    private $URI;

    /**
     * @param array{
     *   DisplayName: ?string,
     *   EmailAddress: ?string,
     *   ID: ?string,
     *   Type: \AsyncAws\S3\Enum\Type::CANONICAL_USER|\AsyncAws\S3\Enum\Type::AMAZON_CUSTOMER_BY_EMAIL|\AsyncAws\S3\Enum\Type::GROUP,
     *   URI: ?string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->DisplayName = $input['DisplayName'];
        $this->EmailAddress = $input['EmailAddress'];
        $this->ID = $input['ID'];
        $this->Type = $input['Type'];
        $this->URI = $input['URI'];
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * Screen name of the grantee.
     */
    public function getDisplayName(): ?string
    {
        return $this->DisplayName;
    }

    /**
     * Email address of the grantee.
     */
    public function getEmailAddress(): ?string
    {
        return $this->EmailAddress;
    }

    /**
     * The canonical user ID of the grantee.
     */
    public function getID(): ?string
    {
        return $this->ID;
    }

    /**
     * Type of grantee.
     *
     * @return Type::CANONICAL_USER|Type::AMAZON_CUSTOMER_BY_EMAIL|Type::GROUP
     */
    public function getType(): string
    {
        return $this->Type;
    }

    /**
     * URI of the grantee group.
     */
    public function getURI(): ?string
    {
        return $this->URI;
    }
}
