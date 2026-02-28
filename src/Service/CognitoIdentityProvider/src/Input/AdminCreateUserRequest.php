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
 * Creates a new user in the specified user pool.
 */
final class AdminCreateUserRequest extends Input
{
    /**
     * The ID of the user pool where you want to create a user.
     *
     * @required
     *
     * @var string|null
     */
    private $userPoolId;

    /**
     * The value that you want to set as the username sign-in attribute. The following conditions apply to the username
     * parameter.
     *
     * - The username can't be a duplicate of another username in the same user pool.
     * - You can't change the value of a username after you create it.
     * - You can only provide a value if usernames are a valid sign-in attribute for your user pool. If your user pool only
     *   supports phone numbers or email addresses as sign-in attributes, Amazon Cognito automatically generates a username
     *   value. For more information, see Customizing sign-in attributes [^1].
     *
     * [^1]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pool-settings-attributes.html#user-pool-settings-aliases
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
     * You must also provide an email address or phone number when you expect the user to do passwordless sign-in with an
     * email or SMS OTP. These attributes must be provided when passwordless options are the only available, or when you
     * don't submit a `TemporaryPassword`.
     *
     * In your `AdminCreateUser` request, you can set the `email_verified` and `phone_number_verified` attributes to `true`.
     * The following conditions apply:
     *
     * - `email`:
     *
     *   The email address where you want the user to receive their confirmation code and username. You must provide a value
     *   for `email` when you want to set `email_verified` to `true`, or if you set `EMAIL` in the `DesiredDeliveryMediums`
     *   parameter.
     * - `phone_number`:
     *
     *   The phone number where you want the user to receive their confirmation code and username. You must provide a value
     *   for `phone_number` when you want to set `phone_number_verified` to `true`, or if you set `SMS` in the
     *   `DesiredDeliveryMediums` parameter.
     *
     * @var AttributeType[]|null
     */
    private $userAttributes;

    /**
     * Temporary user attributes that contribute to the outcomes of your pre sign-up Lambda trigger. This set of key-value
     * pairs are for custom validation of information that you collect from your users but don't need to retain.
     *
     * Your Lambda function can analyze this additional data and act on it. Your function can automatically confirm and
     * verify select users or perform external API operations like logging user attributes and validation data to Amazon
     * CloudWatch Logs.
     *
     * For more information about the pre sign-up Lambda trigger, see Pre sign-up Lambda trigger [^1].
     *
     * [^1]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pool-lambda-pre-sign-up.html
     *
     * @var AttributeType[]|null
     */
    private $validationData;

    /**
     * The user's temporary password. This password must conform to the password policy that you specified when you created
     * the user pool.
     *
     * The exception to the requirement for a password is when your user pool supports passwordless sign-in with email or
     * SMS OTPs. To create a user with no password, omit this parameter or submit a blank value. You can only create a
     * passwordless user when passwordless sign-in is available.
     *
     * The temporary password is valid only once. To complete the Admin Create User flow, the user must enter the temporary
     * password in the sign-in page, along with a new password to be used in all future sign-ins.
     *
     * If you don't specify a value, Amazon Cognito generates one for you unless you have passwordless options active for
     * your user pool.
     *
     * The temporary password can only be used until the user account expiration limit that you set for your user pool. To
     * reset the account after that time limit, you must call `AdminCreateUser` again and specify `RESEND` for the
     * `MessageAction` parameter.
     *
     * @var string|null
     */
    private $temporaryPassword;

    /**
     * This parameter is used only if the `phone_number_verified` or `email_verified` attribute is set to `True`. Otherwise,
     * it is ignored.
     *
     * If this parameter is set to `True` and the phone number or email address specified in the `UserAttributes` parameter
     * already exists as an alias with a different user, this request migrates the alias from the previous user to the
     * newly-created user. The previous user will no longer be able to log in using that alias.
     *
     * If this parameter is set to `False`, the API throws an `AliasExistsException` error if the alias already exists. The
     * default value is `False`.
     *
     * @var bool|null
     */
    private $forceAliasCreation;

    /**
     * Set to `RESEND` to resend the invitation message to a user that already exists, and to reset the temporary-password
     * duration with a new temporary password. Set to `SUPPRESS` to suppress sending the message. You can specify only one
     * value.
     *
     * @var MessageActionType::*|null
     */
    private $messageAction;

    /**
     * Specify `EMAIL` if email will be used to send the welcome message. Specify `SMS` if the phone number will be used.
     * The default value is `SMS`. You can specify more than one value.
     *
     * @var list<DeliveryMediumType::*>|null
     */
    private $desiredDeliveryMediums;

    /**
     * A map of custom key-value pairs that you can provide as input for any custom workflows that this action triggers. You
     * create custom workflows by assigning Lambda functions to user pool triggers.
     *
     * When Amazon Cognito invokes any of these functions, it passes a JSON payload, which the function receives as input.
     * This payload contains a `clientMetadata` attribute that provides the data that you assigned to the ClientMetadata
     * parameter in your request. In your function code, you can process the `clientMetadata` value to enhance your workflow
     * for your specific needs.
     *
     * To review the Lambda trigger types that Amazon Cognito invokes at runtime with API requests, see Connecting API
     * actions to Lambda triggers [^1] in the *Amazon Cognito Developer Guide*.
     *
     * > When you use the `ClientMetadata` parameter, note that Amazon Cognito won't do the following:
     * >
     * > - Store the `ClientMetadata` value. This data is available only to Lambda triggers that are assigned to a user pool
     * >   to support custom workflows. If your user pool configuration doesn't include triggers, the `ClientMetadata`
     * >   parameter serves no purpose.
     * > - Validate the `ClientMetadata` value.
     * > - Encrypt the `ClientMetadata` value. Don't send sensitive information in this parameter.
     * >
     *
     * [^1]: https://docs.aws.amazon.com/cognito/latest/developerguide/cognito-user-pools-working-with-lambda-triggers.html#lambda-triggers-by-event
     *
     * @var array<string, string>|null
     */
    private $clientMetadata;

    /**
     * @param array{
     *   UserPoolId?: string,
     *   Username?: string,
     *   UserAttributes?: array<AttributeType|array>|null,
     *   ValidationData?: array<AttributeType|array>|null,
     *   TemporaryPassword?: string|null,
     *   ForceAliasCreation?: bool|null,
     *   MessageAction?: MessageActionType::*|null,
     *   DesiredDeliveryMediums?: array<DeliveryMediumType::*>|null,
     *   ClientMetadata?: array<string, string>|null,
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
     *   UserAttributes?: array<AttributeType|array>|null,
     *   ValidationData?: array<AttributeType|array>|null,
     *   TemporaryPassword?: string|null,
     *   ForceAliasCreation?: bool|null,
     *   MessageAction?: MessageActionType::*|null,
     *   DesiredDeliveryMediums?: array<DeliveryMediumType::*>|null,
     *   ClientMetadata?: array<string, string>|null,
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
            'Accept' => 'application/json',
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
            throw new InvalidArgument(\sprintf('Missing parameter "UserPoolId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['UserPoolId'] = $v;
        if (null === $v = $this->username) {
            throw new InvalidArgument(\sprintf('Missing parameter "Username" for "%s". The value cannot be null.', __CLASS__));
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
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "MessageAction" for "%s". The value "%s" is not a valid "MessageActionType".', __CLASS__, $v));
            }
            $payload['MessageAction'] = $v;
        }
        if (null !== $v = $this->desiredDeliveryMediums) {
            $index = -1;
            $payload['DesiredDeliveryMediums'] = [];
            foreach ($v as $listValue) {
                ++$index;
                if (!DeliveryMediumType::exists($listValue)) {
                    /** @psalm-suppress NoValue */
                    throw new InvalidArgument(\sprintf('Invalid parameter "DesiredDeliveryMediums" for "%s". The value "%s" is not a valid "DeliveryMediumType".', __CLASS__, $listValue));
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
