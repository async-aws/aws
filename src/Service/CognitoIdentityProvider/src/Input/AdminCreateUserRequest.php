<?php

namespace AsyncAws\CognitoIdentityProvider\Input;

use AsyncAws\CognitoIdentityProvider\Enum\DeliveryMediumType;
use AsyncAws\CognitoIdentityProvider\Enum\MessageActionType;
use AsyncAws\CognitoIdentityProvider\ValueObject\AttributeType;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * Represents the request to create a user in the specified user pool.
 */
final class AdminCreateUserRequest extends Input
{
    /**
     * The user pool ID for the user pool where the user will be created.
     *
     * @required
     *
     * @var string|null
     */
    private $userPoolId;

    /**
     * The username for the user. Must be unique within the user pool. Must be a UTF-8 string between 1 and 128 characters.
     * After the user is created, the username cannot be changed.
     *
     * @required
     *
     * @var string|null
     */
    private $username;

    /**
     * An array of name-value pairs that contain user attributes and attribute values to be set for the user to be created.
     * You can create a user without specifying any attributes other than `Username`. However, any attributes that you
     * specify as required (when creating a user pool or in the **Attributes** tab of the console) must be supplied either
     * by you (in your call to `AdminCreateUser`) or by the user (when he or she signs up in response to your welcome
     * message).
     *
     * @var AttributeType[]|null
     */
    private $userAttributes;

    /**
     * The user's validation data. This is an array of name-value pairs that contain user attributes and attribute values
     * that you can use for custom validation, such as restricting the types of user accounts that can be registered. For
     * example, you might choose to allow or disallow user sign-up based on the user's domain.
     *
     * @var AttributeType[]|null
     */
    private $validationData;

    /**
     * The user's temporary password. This password must conform to the password policy that you specified when you created
     * the user pool.
     *
     * @var string|null
     */
    private $temporaryPassword;

    /**
     * This parameter is only used if the `phone_number_verified` or `email_verified` attribute is set to `True`. Otherwise,
     * it is ignored.
     *
     * @var bool|null
     */
    private $forceAliasCreation;

    /**
     * Set to `"RESEND"` to resend the invitation message to a user that already exists and reset the expiration limit on
     * the user's account. Set to `"SUPPRESS"` to suppress sending the message. Only one value can be specified.
     *
     * @var MessageActionType::*|null
     */
    private $messageAction;

    /**
     * Specify `"EMAIL"` if email will be used to send the welcome message. Specify `"SMS"` if the phone number will be
     * used. The default value is `"SMS"`. More than one value can be specified.
     *
     * @var list<DeliveryMediumType::*>|null
     */
    private $desiredDeliveryMediums;

    /**
     * A map of custom key-value pairs that you can provide as input for any custom workflows that this action triggers.
     *
     * @var array<string, string>|null
     */
    private $clientMetadata;

    /**
     * @param array{
     *   UserPoolId?: string,
     *   Username?: string,
     *   UserAttributes?: AttributeType[],
     *   ValidationData?: AttributeType[],
     *   TemporaryPassword?: string,
     *   ForceAliasCreation?: bool,
     *   MessageAction?: MessageActionType::*,
     *   DesiredDeliveryMediums?: list<DeliveryMediumType::*>,
     *   ClientMetadata?: array<string, string>,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->userPoolId = $input['UserPoolId'] ?? null;
        $this->username = $input['Username'] ?? null;
        $this->userAttributes = isset($input['UserAttributes']) ? array_map([AttributeType::class, 'create'], $input['UserAttributes']) : null;
        $this->validationData = isset($input['ValidationData']) ? array_map([AttributeType::class, 'create'], $input['ValidationData']) : null;
        $this->temporaryPassword = $input['TemporaryPassword'] ?? null;
        $this->forceAliasCreation = $input['ForceAliasCreation'] ?? null;
        $this->messageAction = $input['MessageAction'] ?? null;
        $this->desiredDeliveryMediums = $input['DesiredDeliveryMediums'] ?? null;
        $this->clientMetadata = $input['ClientMetadata'] ?? null;
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
        return $this->clientMetadata ?? [];
    }

    /**
     * @return list<DeliveryMediumType::*>
     */
    public function getDesiredDeliveryMediums(): array
    {
        return $this->desiredDeliveryMediums ?? [];
    }

    public function getForceAliasCreation(): ?bool
    {
        return $this->forceAliasCreation;
    }

    /**
     * @return MessageActionType::*|null
     */
    public function getMessageAction(): ?string
    {
        return $this->messageAction;
    }

    public function getTemporaryPassword(): ?string
    {
        return $this->temporaryPassword;
    }

    /**
     * @return AttributeType[]
     */
    public function getUserAttributes(): array
    {
        return $this->userAttributes ?? [];
    }

    public function getUserPoolId(): ?string
    {
        return $this->userPoolId;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @return AttributeType[]
     */
    public function getValidationData(): array
    {
        return $this->validationData ?? [];
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
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    /**
     * @param array<string, string> $value
     */
    public function setClientMetadata(array $value): self
    {
        $this->clientMetadata = $value;

        return $this;
    }

    /**
     * @param list<DeliveryMediumType::*> $value
     */
    public function setDesiredDeliveryMediums(array $value): self
    {
        $this->desiredDeliveryMediums = $value;

        return $this;
    }

    public function setForceAliasCreation(?bool $value): self
    {
        $this->forceAliasCreation = $value;

        return $this;
    }

    /**
     * @param MessageActionType::*|null $value
     */
    public function setMessageAction(?string $value): self
    {
        $this->messageAction = $value;

        return $this;
    }

    public function setTemporaryPassword(?string $value): self
    {
        $this->temporaryPassword = $value;

        return $this;
    }

    /**
     * @param AttributeType[] $value
     */
    public function setUserAttributes(array $value): self
    {
        $this->userAttributes = $value;

        return $this;
    }

    public function setUserPoolId(?string $value): self
    {
        $this->userPoolId = $value;

        return $this;
    }

    public function setUsername(?string $value): self
    {
        $this->username = $value;

        return $this;
    }

    /**
     * @param AttributeType[] $value
     */
    public function setValidationData(array $value): self
    {
        $this->validationData = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->userPoolId) {
            throw new InvalidArgument(sprintf('Missing parameter "UserPoolId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['UserPoolId'] = $v;
        if (null === $v = $this->username) {
            throw new InvalidArgument(sprintf('Missing parameter "Username" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Username'] = $v;
        if (null !== $v = $this->userAttributes) {
            $index = -1;
            $payload['UserAttributes'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['UserAttributes'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->validationData) {
            $index = -1;
            $payload['ValidationData'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['ValidationData'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->temporaryPassword) {
            $payload['TemporaryPassword'] = $v;
        }
        if (null !== $v = $this->forceAliasCreation) {
            $payload['ForceAliasCreation'] = (bool) $v;
        }
        if (null !== $v = $this->messageAction) {
            if (!MessageActionType::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "MessageAction" for "%s". The value "%s" is not a valid "MessageActionType".', __CLASS__, $v));
            }
            $payload['MessageAction'] = $v;
        }
        if (null !== $v = $this->desiredDeliveryMediums) {
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
        if (null !== $v = $this->clientMetadata) {
            if (empty($v)) {
                $payload['ClientMetadata'] = new \stdClass();
            } else {
                $payload['ClientMetadata'] = [];
                foreach ($v as $name => $mv) {
                    $payload['ClientMetadata'][$name] = $mv;
                }
            }
        }

        return $payload;
    }
}
