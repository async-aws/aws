<?php

namespace AsyncAws\CognitoIdentityProvider\Input;

use AsyncAws\CognitoIdentityProvider\Enum\AuthFlowType;
use AsyncAws\CognitoIdentityProvider\ValueObject\AnalyticsMetadataType;
use AsyncAws\CognitoIdentityProvider\ValueObject\UserContextDataType;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * Initiates the authentication request.
 */
final class InitiateAuthRequest extends Input
{
    /**
     * The authentication flow for this call to run. The API action will depend on this value. For example:.
     *
     * - `REFRESH_TOKEN_AUTH` takes in a valid refresh token and returns new tokens.
     * - `USER_SRP_AUTH` takes in `USERNAME` and `SRP_A` and returns the SRP variables to be used for next challenge
     *   execution.
     * - `USER_PASSWORD_AUTH` takes in `USERNAME` and `PASSWORD` and returns the next challenge or tokens.
     *
     * Valid values include:
     *
     * - `USER_SRP_AUTH`: Authentication flow for the Secure Remote Password (SRP) protocol.
     * - `REFRESH_TOKEN_AUTH`/`REFRESH_TOKEN`: Authentication flow for refreshing the access token and ID token by supplying
     *   a valid refresh token.
     * - `CUSTOM_AUTH`: Custom authentication flow.
     * - `USER_PASSWORD_AUTH`: Non-SRP authentication flow; user name and password are passed directly. If a user migration
     *   Lambda trigger is set, this flow will invoke the user migration Lambda if it doesn't find the user name in the user
     *   pool.
     *
     * `ADMIN_NO_SRP_AUTH` isn't a valid value.
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
     * You create custom workflows by assigning Lambda functions to user pool triggers. When you use the InitiateAuth API
     * action, Amazon Cognito invokes the Lambda functions that are specified for various triggers. The ClientMetadata value
     * is passed as input to the functions for only the following triggers:
     *
     * - Pre signup
     * - Pre authentication
     * - User migration
     *
     * When Amazon Cognito invokes the functions for these triggers, it passes a JSON payload, which the function receives
     * as input. This payload contains a `validationData` attribute, which provides the data that you assigned to the
     * ClientMetadata parameter in your InitiateAuth request. In your function code in Lambda, you can process the
     * `validationData` value to enhance your workflow for your specific needs.
     *
     * When you use the InitiateAuth API action, Amazon Cognito also invokes the functions for the following triggers, but
     * it doesn't provide the ClientMetadata value as input:
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
     * The app client ID.
     *
     * @required
     *
     * @var string|null
     */
    private $clientId;

    /**
     * The Amazon Pinpoint analytics metadata that contributes to your metrics for `InitiateAuth` calls.
     *
     * @var AnalyticsMetadataType|null
     */
    private $analyticsMetadata;

    /**
     * Contextual data about your user session, such as the device fingerprint, IP address, or location. Amazon Cognito
     * advanced security evaluates the risk of an authentication event based on the context that your app generates and
     * passes to Amazon Cognito when it makes API requests.
     *
     * @var UserContextDataType|null
     */
    private $userContextData;

    /**
     * @param array{
     *   AuthFlow?: AuthFlowType::*,
     *   AuthParameters?: array<string, string>,
     *   ClientMetadata?: array<string, string>,
     *   ClientId?: string,
     *   AnalyticsMetadata?: AnalyticsMetadataType|array,
     *   UserContextData?: UserContextDataType|array,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->authFlow = $input['AuthFlow'] ?? null;
        $this->authParameters = $input['AuthParameters'] ?? null;
        $this->clientMetadata = $input['ClientMetadata'] ?? null;
        $this->clientId = $input['ClientId'] ?? null;
        $this->analyticsMetadata = isset($input['AnalyticsMetadata']) ? AnalyticsMetadataType::create($input['AnalyticsMetadata']) : null;
        $this->userContextData = isset($input['UserContextData']) ? UserContextDataType::create($input['UserContextData']) : null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   AuthFlow?: AuthFlowType::*,
     *   AuthParameters?: array<string, string>,
     *   ClientMetadata?: array<string, string>,
     *   ClientId?: string,
     *   AnalyticsMetadata?: AnalyticsMetadataType|array,
     *   UserContextData?: UserContextDataType|array,
     *   '@region'?: string|null,
     * }|InitiateAuthRequest $input
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

    public function getUserContextData(): ?UserContextDataType
    {
        return $this->userContextData;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'AWSCognitoIdentityProviderService.InitiateAuth',
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

    public function setUserContextData(?UserContextDataType $value): self
    {
        $this->userContextData = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
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
        if (null === $v = $this->clientId) {
            throw new InvalidArgument(sprintf('Missing parameter "ClientId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['ClientId'] = $v;
        if (null !== $v = $this->analyticsMetadata) {
            $payload['AnalyticsMetadata'] = $v->requestBody();
        }
        if (null !== $v = $this->userContextData) {
            $payload['UserContextData'] = $v->requestBody();
        }

        return $payload;
    }
}
