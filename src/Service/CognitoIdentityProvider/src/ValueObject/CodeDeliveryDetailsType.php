<?php

namespace AsyncAws\CognitoIdentityProvider\ValueObject;

use AsyncAws\CognitoIdentityProvider\Enum\DeliveryMediumType;

/**
 * The code delivery details returned by the server in response to the request to reset a password.
 */
final class CodeDeliveryDetailsType
{
    /**
     * The destination for the code delivery details.
     */
    private $destination;

    /**
     * The delivery medium (email message or phone number).
     */
    private $deliveryMedium;

    /**
     * The attribute name.
     */
    private $attributeName;

    /**
     * @param array{
     *   Destination?: null|string,
     *   DeliveryMedium?: null|DeliveryMediumType::*,
     *   AttributeName?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->destination = $input['Destination'] ?? null;
        $this->deliveryMedium = $input['DeliveryMedium'] ?? null;
        $this->attributeName = $input['AttributeName'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAttributeName(): ?string
    {
        return $this->attributeName;
    }

    /**
     * @return DeliveryMediumType::*|null
     */
    public function getDeliveryMedium(): ?string
    {
        return $this->deliveryMedium;
    }

    public function getDestination(): ?string
    {
        return $this->destination;
    }
}
