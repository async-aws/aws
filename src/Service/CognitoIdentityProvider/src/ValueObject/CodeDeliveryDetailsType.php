<?php

namespace AsyncAws\CognitoIdentityProvider\ValueObject;

use AsyncAws\CognitoIdentityProvider\Enum\DeliveryMediumType;

/**
 * The delivery details for an email or SMS message that Amazon Cognito sent for authentication or verification.
 */
final class CodeDeliveryDetailsType
{
    /**
     * The email address or phone number destination where Amazon Cognito sent the code.
     *
     * @var string|null
     */
    private $destination;

    /**
     * The method that Amazon Cognito used to send the code.
     *
     * @var DeliveryMediumType::*|null
     */
    private $deliveryMedium;

    /**
     * The name of the attribute that Amazon Cognito verifies with the code.
     *
     * @var string|null
     */
    private $attributeName;

    /**
     * @param array{
     *   Destination?: string|null,
     *   DeliveryMedium?: DeliveryMediumType::*|null,
     *   AttributeName?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->destination = $input['Destination'] ?? null;
        $this->deliveryMedium = $input['DeliveryMedium'] ?? null;
        $this->attributeName = $input['AttributeName'] ?? null;
    }

    /**
     * @param array{
     *   Destination?: string|null,
     *   DeliveryMedium?: DeliveryMediumType::*|null,
     *   AttributeName?: string|null,
     * }|CodeDeliveryDetailsType $input
     */
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
