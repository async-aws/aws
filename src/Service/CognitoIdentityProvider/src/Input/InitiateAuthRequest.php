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
     * The authentication flow that you want to initiate. Each `AuthFlow` has linked `AuthParameters` that you must submit.
     * The following are some example flows.
     *
     * - `USER_AUTH`:
     *
     *   The entry point for choice-based authentication [^1] with passwords, one-time passwords, and WebAuthn
     *   authenticators. Request a preferred authentication type or review available authentication types. From the offered
     *   authentication types, select one in a challenge response and then authenticate with that method in an additional
     *   challenge response. To activate this setting, your user pool must be in the Essentials tier [^2] or higher.
     * - `USER_SRP_AUTH`:
     *
     *   Username-password authentication with the Secure Remote Password (SRP) protocol. For more information, see Use SRP
     *   password verification in custom authentication flow [^3].
     * - `REFRESH_TOKEN_AUTH and REFRESH_TOKEN`:
     *
     *   Receive new ID and access tokens when you pass a `REFRESH_TOKEN` parameter with a valid refresh token as the value.
     *   For more information, see Using the refresh token [^4].
     * - `CUSTOM_AUTH`:
     *
     *   Custom authentication with Lambda triggers. For more information, see Custom authentication challenge Lambda
     *   triggers [^5].
     * - `USER_PASSWORD_AUTH`:
     *
     *   Client-side username-password authentication with the password sent directly in the request. For more information
     *   about client-side and server-side authentication, see SDK authorization models [^6].
     *
     * `ADMIN_USER_PASSWORD_AUTH` is a flow type of `AdminInitiateAuth` and isn't valid for InitiateAuth.
     * `ADMIN_NO_SRP_AUTH` is a legacy server-side username-password flow and isn't valid for InitiateAuth.
     *
     * [^1]: https://docs.aws.amazon.com/cognito/latest/developerguide/authentication-flows-selection-sdk.html#authentication-flows-selection-choice
     * [^2]: https://docs.aws.amazon.com/cognito/latest/developerguide/feature-plans-features-essentials.html
     * [^3]: https://docs.aws.amazon.com/cognito/latest/developerguide/amazon-cognito-user-pools-authentication-flow.html#Using-SRP-password-verification-in-custom-authentication-flow
     * [^4]: https://docs.aws.amazon.com/cognito/latest/developerguide/amazon-cognito-user-pools-using-the-refresh-token.html
     * [^5]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pool-lambda-challenge.html
     * [^6]: https://docs.aws.amazon.com/cognito/latest/developerguide/authentication-flows-public-server-side.html
     *
     * @required
     *
     * @var AuthFlowType::*|null
     */
    private $authFlow;

    /**
     * The authentication parameters. These are inputs corresponding to the `AuthFlow` that you're invoking.
     *
     * The following are some authentication flows and their parameters. Add a `SECRET_HASH` parameter if your app client
     * has a client secret. Add `DEVICE_KEY` if you want to bypass multi-factor authentication with a remembered device.
     *
     * - `USER_AUTH`:
     *
     *   - `USERNAME` (required)
     *   - `PREFERRED_CHALLENGE`. If you don't provide a value for `PREFERRED_CHALLENGE`, Amazon Cognito responds with the
     *     `AvailableChallenges` parameter that specifies the available sign-in methods.
     *
     * - `USER_SRP_AUTH`:
     *
     *   - `USERNAME` (required)
     *   - `SRP_A` (required)
     *
     * - `USER_PASSWORD_AUTH`:
     *
     *   - `USERNAME` (required)
     *   - `PASSWORD` (required)
     *
     * - `REFRESH_TOKEN_AUTH/REFRESH_TOKEN`:
     *
     *   - `REFRESH_TOKEN`(required)
     *
     * - `CUSTOM_AUTH`:
     *
     *   - `USERNAME` (required)
     *   - `ChallengeName: SRP_A` (when doing SRP authentication before custom challenges)
     *   - `SRP_A: (An SRP_A value)` (when doing SRP authentication before custom challenges)
     *
     *
     * For more information about `SECRET_HASH`, see Computing secret hash values [^1]. For information about `DEVICE_KEY`,
     * see Working with user devices in your user pool [^2].
     *
     * [^1]: https://docs.aws.amazon.com/cognito/latest/developerguide/signing-up-users-in-your-app.html#cognito-user-pools-computing-secret-hash
     * [^2]: https://docs.aws.amazon.com/cognito/latest/developerguide/amazon-cognito-user-pools-device-tracking.html
     *
     * @var array<string, string>|null
     */
    private $authParameters;

    /**
     * A map of custom key-value pairs that you can provide as input for certain custom workflows that this action triggers.
     *
     * You create custom workflows by assigning Lambda functions to user pool triggers. When you send an `InitiateAuth`
     * request, Amazon Cognito invokes the Lambda functions that are specified for various triggers. The `ClientMetadata`
     * value is passed as input to the functions for only the following triggers.
     *
     * - Pre sign-up
     * - Pre authentication
     * - User migration
     *
     * When Amazon Cognito invokes the functions for these triggers, it passes a JSON payload as input to the function. This
     * payload contains a `validationData` attribute with the data that you assigned to the `ClientMetadata` parameter in
     * your `InitiateAuth` request. In your function, `validationData` can contribute to operations that require data that
     * isn't in the default payload.
     *
     * `InitiateAuth` requests invokes the following triggers without `ClientMetadata` as input.
     *
     * - Post authentication
     * - Custom message
     * - Pre token generation
     * - Create auth challenge
     * - Define auth challenge
     * - Custom email sender
     * - Custom SMS sender
     *
     * For more information, see Using Lambda triggers [^1] in the *Amazon Cognito Developer Guide*.
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
     * The ID of the app client that your user wants to sign in to.
     *
     * @required
     *
     * @var string|null
     */
    private $clientId;

    /**
     * Information that supports analytics outcomes with Amazon Pinpoint, including the user's endpoint ID. The endpoint ID
     * is a destination for Amazon Pinpoint push notifications, for example a device identifier, email address, or phone
     * number.
     *
     * @var AnalyticsMetadataType|null
     */
    private $analyticsMetadata;

    /**
     * Contextual data about your user session like the device fingerprint, IP address, or location. Amazon Cognito threat
     * protection evaluates the risk of an authentication event based on the context that your app generates and passes to
     * Amazon Cognito when it makes API requests.
     *
     * For more information, see Collecting data for threat protection in applications [^1].
     *
     * [^1]: https://docs.aws.amazon.com/cognito/latest/developerguide/user-pool-settings-viewing-threat-protection-app.html
     *
     * @var UserContextDataType|null
     */
    private $userContextData;

    /**
     * The optional session ID from a `ConfirmSignUp` API request. You can sign in a user directly from the sign-up process
     * with the `USER_AUTH` authentication flow. When you pass the session ID to `InitiateAuth`, Amazon Cognito assumes the
     * SMS or email message one-time verification password from `ConfirmSignUp` as the primary authentication factor. You're
     * not required to submit this code a second time. This option is only valid for users who have confirmed their sign-up
     * and are signing in for the first time within the authentication flow session duration of the session ID.
     *
     * @var string|null
     */
    private $session;

    /**
     * @param array{
     *   AuthFlow?: AuthFlowType::*,
     *   AuthParameters?: array<string, string>|null,
     *   ClientMetadata?: array<string, string>|null,
     *   ClientId?: string,
     *   AnalyticsMetadata?: AnalyticsMetadataType|array|null,
     *   UserContextData?: UserContextDataType|array|null,
     *   Session?: string|null,
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
        $this->session = $input['Session'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   AuthFlow?: AuthFlowType::*,
     *   AuthParameters?: array<string, string>|null,
     *   ClientMetadata?: array<string, string>|null,
     *   ClientId?: string,
     *   AnalyticsMetadata?: AnalyticsMetadataType|array|null,
     *   UserContextData?: UserContextDataType|array|null,
     *   Session?: string|null,
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

    public function getSession(): ?string
    {
        return $this->session;
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

    public function setSession(?string $value): self
    {
        $this->session = $value;

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
            throw new InvalidArgument(\sprintf('Missing parameter "AuthFlow" for "%s". The value cannot be null.', __CLASS__));
        }
        if (!AuthFlowType::exists($v)) {
            /** @psalm-suppress NoValue */
            throw new InvalidArgument(\sprintf('Invalid parameter "AuthFlow" for "%s". The value "%s" is not a valid "AuthFlowType".', __CLASS__, $v));
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
            throw new InvalidArgument(\sprintf('Missing parameter "ClientId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['ClientId'] = $v;
        if (null !== $v = $this->analyticsMetadata) {
            $payload['AnalyticsMetadata'] = $v->requestBody();
        }
        if (null !== $v = $this->userContextData) {
            $payload['UserContextData'] = $v->requestBody();
        }
        if (null !== $v = $this->session) {
            $payload['Session'] = $v;
        }

        return $payload;
    }
}
