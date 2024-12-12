<?php

namespace AsyncAws\CognitoIdentityProvider\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * Represents the request to reset a user's password as an administrator.
 */
final class AdminResetUserPasswordRequest extends Input
{
    /**
     * The ID of the user pool where you want to reset the user's password.
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
     * A map of custom key-value pairs that you can provide as input for any custom workflows that this action triggers.
     *
     * You create custom workflows by assigning Lambda functions to user pool triggers. The `AdminResetUserPassword` API
     * operation invokes the function that is assigned to the *custom message* trigger. When Amazon Cognito invokes this
     * function, it passes a JSON payload, which the function receives as input. This payload contains a `clientMetadata`
     * attribute, which provides the data that you assigned to the ClientMetadata parameter in your AdminResetUserPassword
     * request. In your function code in Lambda, you can process the `clientMetadata` value to enhance your workflow for
     * your specific needs.
     *
     * For more information, see Customizing user pool Workflows with Lambda Triggers [^1] in the *Amazon Cognito Developer
     * Guide*.
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
     * [^1]: https://docs.aws.amazon.com/cognito/latest/developerguide/cognito-user-identity-pools-working-with-aws-lambda-triggers.html
     *
     * @var array<string, string>|null
     */
    private $clientMetadata;

    /**
     * @param array{
     *   UserPoolId?: string,
     *   Username?: string,
     *   ClientMetadata?: null|array<string, string>,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->userPoolId = $input['UserPoolId'] ?? null;
        $this->username = $input['Username'] ?? null;
        $this->clientMetadata = $input['ClientMetadata'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   UserPoolId?: string,
     *   Username?: string,
     *   ClientMetadata?: null|array<string, string>,
     *   '@region'?: string|null,
     * }|AdminResetUserPasswordRequest $input
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
            'X-Amz-Target' => 'AWSCognitoIdentityProviderService.AdminResetUserPassword',
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
            throw new InvalidArgument(\sprintf('Missing parameter "UserPoolId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['UserPoolId'] = $v;
        if (null === $v = $this->username) {
            throw new InvalidArgument(\sprintf('Missing parameter "Username" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Username'] = $v;
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
