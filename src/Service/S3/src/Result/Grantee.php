<?php

namespace AsyncAws\S3\Result;

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
     * @return Type::CANONICAL_USER|Type::AMAZON_CUSTOMER_BY_EMAIL|Type::GROUP
     */
    public function getType(): string
    {
        return $this->Type;
    }

    public function getURI(): ?string
    {
        return $this->URI;
    }
}
