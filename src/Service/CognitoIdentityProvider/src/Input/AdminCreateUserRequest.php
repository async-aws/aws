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
     * After the user is created, the username can't be changed.
     *
     * @required
     *
     * @var string|null
     */
    private $username;

    /**
     * An array of name-value pairs that contain user attributes and attribute values to be set for the user to be created.
     * You can create a user without specifying any attributes other than `Username`. However, any attributes that you
     * specify as required (when creating a user pool or in the **Attributes** tab of the console) either you should supply
     * (in your call to `AdminCreateUser`) or the user should supply (when they sign up in response to your welcome
     * message).
     *
     * For custom attributes, you must prepend the `custom:` prefix to the attribute name.
     *
     * To send a message inviting the user to sign up, you must specify the user's email address or phone number. You can do
     * this in your call to AdminCreateUser or in the **Users** tab of the Amazon Cognito console for managing your user
     * pools.
     *
     * In your call to `AdminCreateUser`, you can set the `email_verified` attribute to `True`, and you can set the
     * `phone_number_verified` attribute to `True`. You can also do this by calling AdminUpdateUserAttributes [^1].
     *
     * - **email**: The email address of the user to whom the message that contains the code and username will be sent.
     *   Required if the `email_verified` attribute is set to `True`, or if `"EMAIL"` is specified in the
     *   `DesiredDeliveryMediums` parameter.
     * - **phone_number**: The phone number of the user to whom the message that contains the code and username will be
     *   sent. Required if the `phone_number_verified` attribute is set to `True`, or if `"SMS"` is specified in the
     *   `DesiredDeliveryMediums` parameter.
     *
     * [^1]: https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_AdminUpdateUserAttributes.html
     *
     * @var AttributeType[]|null
     */
    private $userAttributes;

    /**
     * The user's validation data. This is an array of name-value pairs that contain user attributes and attribute values
     * that you can use for custom validation, such as restricting the types of user accounts that can be registered. For
     * example, you might choose to allow or disallow user sign-up based on the user's domain.
     *
     * To configure custom validation, you must create a Pre Sign-up Lambda trigger for the user pool as described in the
     * Amazon Cognito Developer Guide. The Lambda trigger receives the validation data and uses it in the validation
     * process.
     *
     * The user's validation data isn't persisted.
     *
     * @var AttributeType[]|null
     */
    private $validationData;

    /**
     * The user's temporary password. This password must conform to the password policy that you specified when you created
     * the user pool.
     *
     * The temporary password is valid only once. To complete the Admin Create User flow, the user must enter the temporary
     * password in the sign-in page, along with a new password to be used in all future sign-ins.
     *
     * This parameter isn't required. If you don't specify a value, Amazon Cognito generates one for you.
     *
     * The temporary password can only be used until the user account expiration limit that you specified when you created
     * the user pool. To reset the account after that time limit, you must call `AdminCreateUser` again, specifying
     * `"RESEND"` for the `MessageAction` parameter.
     *
     * @var string|null
     */
    private $temporaryPassword;

    /**
     * This parameter is used only if the `phone_number_verified` or `email_verified` attribute is set to `True`. Otherwise,
     * it is ignored.
     *
     * If this parameter is set to `True` and the phone number or email address specified in the UserAttributes parameter
     * already exists as an alias with a different user, the API call will migrate the alias from the previous user to the
     * newly created user. The previous user will no longer be able to log in using that alias.
     *
     * If this parameter is set to `False`, the API throws an `AliasExistsException` error if the alias already exists. The
     * default value is `False`.
     *
     * @var bool|null
     */
    private $forceAliasCreation;

    /**
     * Set to `RESEND` to resend the invitation message to a user that already exists and reset the expiration limit on the
     * user's account. Set to `SUPPRESS` to suppress sending the message. You can specify only one value.
     *
     * @var MessageActionType::*|null
     */
    private $messageAction;

    /**
     * Specify `"EMAIL"` if email will be used to send the welcome message. Specify `"SMS"` if the phone number will be
     * used. The default value is `"SMS"`. You can specify more than one value.
     *
     * @var list<DeliveryMediumType::*>|null
     */
    private $desiredDeliveryMediums;

    /**
     * A map of custom key-value pairs that you can provide as input for any custom workflows that this action triggers.
     *
     * You create custom workflows by assigning Lambda functions to user pool triggers. When you use the AdminCreateUser API
     * action, Amazon Cognito invokes the function that is assigned to the *pre sign-up* trigger. When Amazon Cognito
     * invokes this function, it passes a JSON payload, which the function receives as input. This payload contains a
     * `clientMetadata` attribute, which provides the data that you assigned to the ClientMetadata parameter in your
     * AdminCreateUser request. In your function code in Lambda, you can process the `clientMetadata` value to enhance your
     * workflow for your specific needs.
     *
     * For more information, see  Customizing user pool Workflows with Lambda Triggers [^1] in the *Amazon Cognito Developer
     * Guide*.
     *
     * > When you use the ClientMetadata parameter, remember that Amazon Cognito won't do the following:
     * >
     * > - Store the ClientMetadata value. This data is available only to Lambda triggers that are assigned to a user pool
     * >   to support custom workflows. If your user pool configuration doesn't include triggers, the ClientMetadata
     * >   parameter serves no purpose.
     * > - Validate the ClientMetadata value.
     * > - Encrypt the ClientMetadata value. Don't use Amazon Cognito to provide sensitive information.
     * >
     *
     * [^1]: https://docs.aws.amazon.com/cognito/latest/developerguide/cognito-user-identity-pools-working-with-aws-lambda-triggers.html
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
     *   '@region'?: string|null,
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
     *   '@region'?: string|null,
     * }|AdminCreateUserRequest $input
     */
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
