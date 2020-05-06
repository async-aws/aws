<?php

namespace AsyncAws\CognitoIdentityProvider\ValueObject;

use AsyncAws\CognitoIdentityProvider\Enum\DeliveryMediumType;

final class MFAOptionType
{
    /**
     * The delivery medium to send the MFA code. You can use this parameter to set only the `SMS` delivery medium value.
     */
    private $DeliveryMedium;

    /**
     * The attribute name of the MFA option type. The only valid value is `phone_number`.
     */
    private $AttributeName;

    /**
     * @param array{
     *   DeliveryMedium?: null|\AsyncAws\CognitoIdentityProvider\Enum\DeliveryMediumType::*,
     *   AttributeName?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
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
}
