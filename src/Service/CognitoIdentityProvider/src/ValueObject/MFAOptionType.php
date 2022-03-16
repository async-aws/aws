<?php

namespace AsyncAws\CognitoIdentityProvider\ValueObject;

use AsyncAws\CognitoIdentityProvider\Enum\DeliveryMediumType;

/**
 * *This data type is no longer supported.* Applies only to SMS multi-factor authentication (MFA) configurations. Does
 * not apply to time-based one-time password (TOTP) software token MFA configurations.
 */
final class MFAOptionType
{
    /**
     * The delivery medium to send the MFA code. You can use this parameter to set only the `SMS` delivery medium value.
     */
    private $deliveryMedium;

    /**
     * The attribute name of the MFA option type. The only valid value is `phone_number`.
     */
    private $attributeName;

    /**
     * @param array{
     *   DeliveryMedium?: null|DeliveryMediumType::*,
     *   AttributeName?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
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
}
