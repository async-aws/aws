<?php

namespace AsyncAws\CognitoIdentityProvider\Input;

use AsyncAws\CognitoIdentityProvider\Enum\DeliveryMediumType;
use AsyncAws\CognitoIdentityProvider\Enum\MessageActionType;
use AsyncAws\CognitoIdentityProvider\ValueObject\AttributeType;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class AdminCreateUserRequest extends Input
{
    /**
     * The user pool ID for the user pool where the user will be created.
     *
     * @required
     *
     * @var string|null
     */
    private $UserPoolId;

    /**
     * The username for the user. Must be unique within the user pool. Must be a UTF-8 string between 1 and 128 characters.
     * After the user is created, the username cannot be changed.
     *
     * @required
     *
     * @var string|null
     */
    private $Username;

    /**
     * An array of name-value pairs that contain user attributes and attribute values to be set for the user to be created.
     * You can create a user without specifying any attributes other than `Username`. However, any attributes that you
     * specify as required (in or in the **Attributes** tab of the console) must be supplied either by you (in your call to
     * `AdminCreateUser`) or by the user (when he or she signs up in response to your welcome message).
     *
     * @var AttributeType[]|null
     */
    private $UserAttributes;

    /**
     * The user's validation data. This is an array of name-value pairs that contain user attributes and attribute values
     * that you can use for custom validation, such as restricting the types of user accounts that can be registered. For
     * example, you might choose to allow or disallow user sign-up based on the user's domain.
     *
     * @var AttributeType[]|null
     */
    private $ValidationData;

    /**
     * The user's temporary password. This password must conform to the password policy that you specified when you created
     * the user pool.
     *
     * @var string|null
     */
    private $TemporaryPassword;

    /**
     * This parameter is only used if the `phone_number_verified` or `email_verified` attribute is set to `True`. Otherwise,
     * it is ignored.
     *
     * @var bool|null
     */
    private $ForceAliasCreation;

    /**
     * Set to `"RESEND"` to resend the invitation message to a user that already exists and reset the expiration limit on
     * the user's account. Set to `"SUPPRESS"` to suppress sending the message. Only one value can be specified.
     *
     * @var null|MessageActionType::*
     */
    private $MessageAction;

    /**
     * Specify `"EMAIL"` if email will be used to send the welcome message. Specify `"SMS"` if the phone number will be
     * used. The default value is `"SMS"`. More than one value can be specified.
     *
     * @var null|list<DeliveryMediumType::*>
     */
    private $DesiredDeliveryMediums;

    /**
     * A map of custom key-value pairs that you can provide as input for any custom workflows that this action triggers.
     *
     * @var array<string, string>|null
     */
    private $ClientMetadata;

    /**
     * @param array{
     *   UserPoolId?: string,
     *   Username?: string,
     *   UserAttributes?: \AsyncAws\CognitoIdentityProvider\ValueObject\AttributeType[],
     *   ValidationData?: \AsyncAws\CognitoIdentityProvider\ValueObject\AttributeType[],
     *   TemporaryPassword?: string,
     *   ForceAliasCreation?: bool,
     *   MessageAction?: \AsyncAws\CognitoIdentityProvider\Enum\MessageActionType::*,
     *   DesiredDeliveryMediums?: list<\AsyncAws\CognitoIdentityProvider\Enum\DeliveryMediumType::*>,
     *   ClientMetadata?: array<string, string>,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->UserPoolId = $input['UserPoolId'] ?? null;
        $this->Username = $input['Username'] ?? null;
        $this->UserAttributes = isset($input['UserAttributes']) ? array_map([AttributeType::class, 'create'], $input['UserAttributes']) : null;
        $this->ValidationData = isset($input['ValidationData']) ? array_map([AttributeType::class, 'create'], $input['ValidationData']) : null;
        $this->TemporaryPassword = $input['TemporaryPassword'] ?? null;
        $this->ForceAliasCreation = $input['ForceAliasCreation'] ?? null;
        $this->MessageAction = $input['MessageAction'] ?? null;
        $this->DesiredDeliveryMediums = $input['DesiredDeliveryMediums'] ?? null;
        $this->ClientMetadata = $input['ClientMetadata'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return array<string, string>
     */
    public function getClientMetadata(): array
    {
        return $this->ClientMetadata ?? [];
    }

    /**
     * @return list<DeliveryMediumType::*>
     */
    public function getDesiredDeliveryMediums(): array
    {
        return $this->DesiredDeliveryMediums ?? [];
    }

    public function getForceAliasCreation(): ?bool
    {
        return $this->ForceAliasCreation;
    }

    /**
     * @return MessageActionType::*|null
     */
    public function getMessageAction(): ?string
    {
        return $this->MessageAction;
    }

    public function getTemporaryPassword(): ?string
    {
        return $this->TemporaryPassword;
    }

    /**
     * @return AttributeType[]
     */
    public function getUserAttributes(): array
    {
        return $this->UserAttributes ?? [];
    }

    public function getUserPoolId(): ?string
    {
        return $this->UserPoolId;
    }

    public function getUsername(): ?string
    {
        return $this->Username;
    }

    /**
     * @return AttributeType[]
     */
    public function getValidationData(): array
    {
        return $this->ValidationData ?? [];
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'AWSCognitoIdentityProviderService.AdminCreateUser',
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    /**
     * @param array<string, string> $value
     */
    public function setClientMetadata(array $value): self
    {
        $this->ClientMetadata = $value;

        return $this;
    }

    /**
     * @param list<DeliveryMediumType::*> $value
     */
    public function setDesiredDeliveryMediums(array $value): self
    {
        $this->DesiredDeliveryMediums = $value;

        return $this;
    }

    public function setForceAliasCreation(?bool $value): self
    {
        $this->ForceAliasCreation = $value;

        return $this;
    }

    /**
     * @param MessageActionType::*|null $value
     */
    public function setMessageAction(?string $value): self
    {
        $this->MessageAction = $value;

        return $this;
    }

    public function setTemporaryPassword(?string $value): self
    {
        $this->TemporaryPassword = $value;

        return $this;
    }

    /**
     * @param AttributeType[] $value
     */
    public function setUserAttributes(array $value): self
    {
        $this->UserAttributes = $value;

        return $this;
    }

    public function setUserPoolId(?string $value): self
    {
        $this->UserPoolId = $value;

        return $this;
    }

    public function setUsername(?string $value): self
    {
        $this->Username = $value;

        return $this;
    }

    /**
     * @param AttributeType[] $value
     */
    public function setValidationData(array $value): self
    {
        $this->ValidationData = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->UserPoolId) {
            throw new InvalidArgument(sprintf('Missing parameter "UserPoolId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['UserPoolId'] = $v;
        if (null === $v = $this->Username) {
            throw new InvalidArgument(sprintf('Missing parameter "Username" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Username'] = $v;
        if (null !== $v = $this->UserAttributes) {
            $index = -1;
            $payload['UserAttributes'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['UserAttributes'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->ValidationData) {
            $index = -1;
            $payload['ValidationData'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['ValidationData'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->TemporaryPassword) {
            $payload['TemporaryPassword'] = $v;
        }
        if (null !== $v = $this->ForceAliasCreation) {
            $payload['ForceAliasCreation'] = (bool) $v;
        }
        if (null !== $v = $this->MessageAction) {
            if (!MessageActionType::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "MessageAction" for "%s". The value "%s" is not a valid "MessageActionType".', __CLASS__, $v));
            }
            $payload['MessageAction'] = $v;
        }
        if (null !== $v = $this->DesiredDeliveryMediums) {
            $index = -1;
            $payload['DesiredDeliveryMediums'] = [];
            foreach ($v as $listValue) {
                ++$index;
                if (!DeliveryMediumType::exists($listValue)) {
                    throw new InvalidArgument(sprintf('Invalid parameter "DesiredDeliveryMediums" for "%s". The value "%s" is not a valid "DeliveryMediumType".', __CLASS__, $listValue));
                }
                $payload['DesiredDeliveryMediums'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->ClientMetadata) {
            foreach ($v as $name => $v) {
                $payload['ClientMetadata'][$name] = $v;
            }
        }

        return $payload;
    }
}
