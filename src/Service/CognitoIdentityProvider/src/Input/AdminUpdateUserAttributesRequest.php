<?php

namespace AsyncAws\CognitoIdentityProvider\Input;

use AsyncAws\CognitoIdentityProvider\ValueObject\AttributeType;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * Represents the request to update the user's attributes as an administrator.
 */
final class AdminUpdateUserAttributesRequest extends Input
{
    /**
     * The user pool ID for the user pool where you want to update user attributes.
     *
     * @required
     *
     * @var string|null
     */
    private $userPoolId;

    /**
     * The username of the user that you want to query or modify. The value of this parameter is typically your user's
     * username, but it can be any of their alias attributes. If `username` isn't an alias attribute in your user pool, this
     * value must be the `sub` of a local user or the username of a user from a third-party IdP.
     *
     * @required
     *
     * @var string|null
     */
    private $username;

    /**
     * An array of name-value pairs representing user attributes.
     *
     * For custom attributes, you must prepend the `custom:` prefix to the attribute name.
     *
     * If your user pool requires verification before Amazon Cognito updates an attribute value that you specify in this
     * request, Amazon Cognito doesn’t immediately update the value of that attribute. After your user receives and
     * responds to a verification message to verify the new value, Amazon Cognito updates the attribute value. Your user can
     * sign in and receive messages with the original attribute value until they verify the new value.
     *
     * To update the value of an attribute that requires verification in the same API request, include the `email_verified`
     * or `phone_number_verified` attribute, with a value of `true`. If you set the `email_verified` or
     * `phone_number_verified` value for an `email` or `phone_number` attribute that requires verification to `true`, Amazon
     * Cognito doesn’t send a verification message to your user.
     *
     * @required
     *
     * @var AttributeType[]|null
     */
    private $userAttributes;

    /**
     * A map of custom key-value pairs that you can provide as input for any custom workflows that this action triggers.
     *
     * You create custom workflows by assigning Lambda functions to user pool triggers. When you use the
     * AdminUpdateUserAttributes API action, Amazon Cognito invokes the function that is assigned to the *custom message*
     * trigger. When Amazon Cognito invokes this function, it passes a JSON payload, which the function receives as input.
     * This payload contains a `clientMetadata` attribute, which provides the data that you assigned to the ClientMetadata
     * parameter in your AdminUpdateUserAttributes request. In your function code in Lambda, you can process the
     * `clientMetadata` value to enhance your workflow for your specific needs.
     *
     * For more information, see Customizing user pool Workflows with Lambda Triggers [^1] in the *Amazon Cognito Developer
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
     *   UserAttributes?: array<AttributeType|array>,
     *   ClientMetadata?: null|array<string, string>,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->userPoolId = $input['UserPoolId'] ?? null;
        $this->username = $input['Username'] ?? null;
        $this->userAttributes = isset($input['UserAttributes']) ? array_map([AttributeType::class, 'create'], $input['UserAttributes']) : null;
        $this->clientMetadata = $input['ClientMetadata'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   UserPoolId?: string,
     *   Username?: string,
     *   UserAttributes?: array<AttributeType|array>,
     *   ClientMetadata?: null|array<string, string>,
     *   '@region'?: string|null,
     * }|AdminUpdateUserAttributesRequest $input
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
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'AWSCognitoIdentityProviderService.AdminUpdateUserAttributes',
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
        if (null === $v = $this->userAttributes) {
            throw new InvalidArgument(sprintf('Missing parameter "UserAttributes" for "%s". The value cannot be null.', __CLASS__));
        }

        $index = -1;
        $payload['UserAttributes'] = [];
        foreach ($v as $listValue) {
            ++$index;
            $payload['UserAttributes'][$index] = $listValue->requestBody();
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
