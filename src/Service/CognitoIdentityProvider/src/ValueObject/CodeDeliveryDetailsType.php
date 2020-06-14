<?php

namespace AsyncAws\CognitoIdentityProvider\ValueObject;

use AsyncAws\CognitoIdentityProvider\Enum\DeliveryMediumType;

final class CodeDeliveryDetailsType
{
    /**
     * The destination for the code delivery details.
     */
    private $Destination;

    /**
     * The delivery medium (email message or phone number).
     */
    private $DeliveryMedium;

    /**
     * The attribute name.
     */
    private $AttributeName;

    /**
     * @param array{
     *   Destination?: null|string,
     *   DeliveryMedium?: null|\AsyncAws\CognitoIdentityProvider\Enum\DeliveryMediumType::*,
     *   AttributeName?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Destination = $input['Destination'] ?? null;
        $this->DeliveryMedium = $input['DeliveryMedium'] ?? null;
        $this->AttributeName = $input['AttributeName'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAttributeName(): ?string
    {
        return $this->AttributeName;
    }

    /**
     * @return DeliveryMediumType::*|null
     */
    public function getDeliveryMedium(): ?string
    {
        return $this->DeliveryMedium;
    }

    public function getDestination(): ?string
    {
        return $this->Destination;
    }
}
