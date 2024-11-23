<?php

namespace AsyncAws\CognitoIdentityProvider\ValueObject;

use AsyncAws\CognitoIdentityProvider\Enum\DeliveryMediumType;

/**
 * The delivery details for an email or SMS message that Amazon Cognito sent for authentication or verification.
 *
 * This data type is a response parameter of operations that send a code for user profile confirmation, verification, or
 * management, for example ForgotPassword [^1] and SignUp [^2].
 *
 * [^1]: https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_ForgotPassword.html
 * [^2]: https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_SignUp.html
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

    /**
     * @param array{
     *   Destination?: null|string,
     *   DeliveryMedium?: null|DeliveryMediumType::*,
     *   AttributeName?: null|string,
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
