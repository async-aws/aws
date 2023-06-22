<?php

namespace AsyncAws\CognitoIdentityProvider\Input;

use AsyncAws\CognitoIdentityProvider\Enum\AuthFlowType;
use AsyncAws\CognitoIdentityProvider\ValueObject\AnalyticsMetadataType;
use AsyncAws\CognitoIdentityProvider\ValueObject\ContextDataType;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * Initiates the authorization request, as an administrator.
 */
final class AdminInitiateAuthRequest extends Input
{
    /**
     * The ID of the Amazon Cognito user pool.
     *
     * @required
     *
     * @var string|null
     */
    private $userPoolId;

    /**
     * The app client ID.
     *
     * @required
     *
     * @var string|null
     */
    private $clientId;

    /**
     * The authentication flow for this call to run. The API action will depend on this value. For example:.
     *
     * - `REFRESH_TOKEN_AUTH` will take in a valid refresh token and return new tokens.
     * - `USER_SRP_AUTH` will take in `USERNAME` and `SRP_A` and return the Secure Remote Password (SRP) protocol variables
     *   to be used for next challenge execution.
     * - `ADMIN_USER_PASSWORD_AUTH` will take in `USERNAME` and `PASSWORD` and return the next challenge or tokens.
     *
     * Valid values include:
     *
     * - `USER_SRP_AUTH`: Authentication flow for the Secure Remote Password (SRP) protocol.
     * - `REFRESH_TOKEN_AUTH`/`REFRESH_TOKEN`: Authentication flow for refreshing the access token and ID token by supplying
     *   a valid refresh token.
     * - `CUSTOM_AUTH`: Custom authentication flow.
     * - `ADMIN_NO_SRP_AUTH`: Non-SRP authentication flow; you can pass in the USERNAME and PASSWORD directly if the flow is
     *   enabled for calling the app client.
     * - `ADMIN_USER_PASSWORD_AUTH`: Admin-based user password authentication. This replaces the `ADMIN_NO_SRP_AUTH`
     *   authentication flow. In this flow, Amazon Cognito receives the password in the request instead of using the SRP
     *   process to verify passwords.
     *
     * @required
     *
     * @var AuthFlowType::*|null
     */
    private $authFlow;

    /**
     * The authentication parameters. These are inputs corresponding to the `AuthFlow` that you're invoking. The required
     * values depend on the value of `AuthFlow`:.
     *
     * - For `USER_SRP_AUTH`: `USERNAME` (required), `SRP_A` (required), `SECRET_HASH` (required if the app client is
     *   configured with a client secret), `DEVICE_KEY`.
     * - For `REFRESH_TOKEN_AUTH/REFRESH_TOKEN`: `REFRESH_TOKEN` (required), `SECRET_HASH` (required if the app client is
     *   configured with a client secret), `DEVICE_KEY`.
     * - For `ADMIN_NO_SRP_AUTH`: `USERNAME` (required), `SECRET_HASH` (if app client is configured with client secret),
     *   `PASSWORD` (required), `DEVICE_KEY`.
     * - For `CUSTOM_AUTH`: `USERNAME` (required), `SECRET_HASH` (if app client is configured with client secret),
     *   `DEVICE_KEY`. To start the authentication flow with password verification, include `ChallengeName: SRP_A` and
     *   `SRP_A: (The SRP_A Value)`.
     *
     * @var array<string, string>|null
     */
    private $authParameters;

    /**
     * A map of custom key-value pairs that you can provide as input for certain custom workflows that this action triggers.
     *
     * You create custom workflows by assigning Lambda functions to user pool triggers. When you use the AdminInitiateAuth
     * API action, Amazon Cognito invokes the Lambda functions that are specified for various triggers. The ClientMetadata
     * value is passed as input to the functions for only the following triggers:
     *
     * - Pre signup
     * - Pre authentication
     * - User migration
     *
     * When Amazon Cognito invokes the functions for these triggers, it passes a JSON payload, which the function receives
     * as input. This payload contains a `validationData` attribute, which provides the data that you assigned to the
     * ClientMetadata parameter in your AdminInitiateAuth request. In your function code in Lambda, you can process the
     * `validationData` value to enhance your workflow for your specific needs.
     *
     * When you use the AdminInitiateAuth API action, Amazon Cognito also invokes the functions for the following triggers,
     * but it doesn't provide the ClientMetadata value as input:
     *
     * - Post authentication
     * - Custom message
     * - Pre token generation
     * - Create auth challenge
     * - Define auth challenge
     * - Verify auth challenge
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
     * The analytics metadata for collecting Amazon Pinpoint metrics for `AdminInitiateAuth` calls.
     *
     * @var AnalyticsMetadataType|null
     */
    private $analyticsMetadata;

    /**
     * Contextual data about your user session, such as the device fingerprint, IP address, or location. Amazon Cognito
     * advanced security evaluates the risk of an authentication event based on the context that your app generates and
     * passes to Amazon Cognito when it makes API requests.
     *
     * @var ContextDataType|null
     */
    private $contextData;

    /**
     * @param array{
     *   UserPoolId?: string,
     *   ClientId?: string,
     *   AuthFlow?: AuthFlowType::*,
     *   AuthParameters?: array<string, string>,
     *   ClientMetadata?: array<string, string>,
     *   AnalyticsMetadata?: AnalyticsMetadataType|array,
     *   ContextData?: ContextDataType|array,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->userPoolId = $input['UserPoolId'] ?? null;
        $this->clientId = $input['ClientId'] ?? null;
        $this->authFlow = $input['AuthFlow'] ?? null;
        $this->authParameters = $input['AuthParameters'] ?? null;
        $this->clientMetadata = $input['ClientMetadata'] ?? null;
        $this->analyticsMetadata = isset($input['AnalyticsMetadata']) ? AnalyticsMetadataType::create($input['AnalyticsMetadata']) : null;
        $this->contextData = isset($input['ContextData']) ? ContextDataType::create($input['ContextData']) : null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   UserPoolId?: string,
     *   ClientId?: string,
     *   AuthFlow?: AuthFlowType::*,
     *   AuthParameters?: array<string, string>,
     *   ClientMetadata?: array<string, string>,
     *   AnalyticsMetadata?: AnalyticsMetadataType|array,
     *   ContextData?: ContextDataType|array,
     *   '@region'?: string|null,
     * }|AdminInitiateAuthRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAnalyticsMetadata(): ?AnalyticsMetadataType
    {
        return $this->analyticsMetadata;
    }

    /**
     * @return AuthFlowType::*|null
     */
    public function getAuthFlow(): ?string
    {
        return $this->authFlow;
    }

    /**
     * @return array<string, string>
     */
    public function getAuthParameters(): array
    {
        return $this->authParameters ?? [];
    }

    public function getClientId(): ?string
    {
        return $this->clientId;
    }

    /**
     * @return array<string, string>
     */
    public function getClientMetadata(): array
    {
        return $this->clientMetadata ?? [];
    }

    public function getContextData(): ?ContextDataType
    {
        return $this->contextData;
    }

    public function getUserPoolId(): ?string
    {
        return $this->userPoolId;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'AWSCognitoIdentityProviderService.AdminInitiateAuth',
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

    public function setAnalyticsMetadata(?AnalyticsMetadataType $value): self
    {
        $this->analyticsMetadata = $value;

        return $this;
    }

    /**
     * @param AuthFlowType::*|null $value
     */
    public function setAuthFlow(?string $value): self
    {
        $this->authFlow = $value;

        return $this;
    }

    /**
     * @param array<string, string> $value
     */
    public function setAuthParameters(array $value): self
    {
        $this->authParameters = $value;

        return $this;
    }

    public function setClientId(?string $value): self
    {
        $this->clientId = $value;

        return $this;
    }

    /**
     * @param array<string, string> $value
     */
    public function setClientMetadata(array $value): self
    {
        $this->clientMetadata = $value;

        return $this;
    }

    public function setContextData(?ContextDataType $value): self
    {
        $this->contextData = $value;

        return $this;
    }

    public function setUserPoolId(?string $value): self
    {
        $this->userPoolId = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->userPoolId) {
            throw new InvalidArgument(sprintf('Missing parameter "UserPoolId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['UserPoolId'] = $v;
        if (null === $v = $this->clientId) {
            throw new InvalidArgument(sprintf('Missing parameter "ClientId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['ClientId'] = $v;
        if (null === $v = $this->authFlow) {
            throw new InvalidArgument(sprintf('Missing parameter "AuthFlow" for "%s". The value cannot be null.', __CLASS__));
        }
        if (!AuthFlowType::exists($v)) {
            throw new InvalidArgument(sprintf('Invalid parameter "AuthFlow" for "%s". The value "%s" is not a valid "AuthFlowType".', __CLASS__, $v));
        }
        $payload['AuthFlow'] = $v;
        if (null !== $v = $this->authParameters) {
            if (empty($v)) {
                $payload['AuthParameters'] = new \stdClass();
            } else {
                $payload['AuthParameters'] = [];
                foreach ($v as $name => $mv) {
                    $payload['AuthParameters'][$name] = $mv;
                }
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
        if (null !== $v = $this->analyticsMetadata) {
            $payload['AnalyticsMetadata'] = $v->requestBody();
        }
        if (null !== $v = $this->contextData) {
            $payload['ContextData'] = $v->requestBody();
        }

        return $payload;
    }
}
