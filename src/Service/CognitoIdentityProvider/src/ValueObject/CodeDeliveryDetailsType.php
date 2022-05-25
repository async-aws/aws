<?php

namespace AsyncAws\CognitoIdentityProvider\ValueObject;

use AsyncAws\CognitoIdentityProvider\Enum\DeliveryMediumType;

/**
 * The code delivery details returned by the server in response to the request to reset a password.
 */
final class CodeDeliveryDetailsType
{
    /**
     * The email address or phone number destination where Amazon Cognito sent the code.
     */
    private $destination;

    /**
     * The method that Amazon Cognito used to send the code.
     */
    private $deliveryMedium;

    /**
     * The name of the attribute that Amazon Cognito verifies with the code.
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
