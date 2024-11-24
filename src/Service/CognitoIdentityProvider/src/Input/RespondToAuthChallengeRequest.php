<?php

namespace AsyncAws\CognitoIdentityProvider\Input;

use AsyncAws\CognitoIdentityProvider\Enum\ChallengeNameType;
use AsyncAws\CognitoIdentityProvider\ValueObject\AnalyticsMetadataType;
use AsyncAws\CognitoIdentityProvider\ValueObject\UserContextDataType;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * The request to respond to an authentication challenge.
 */
final class RespondToAuthChallengeRequest extends Input
{
    /**
     * The app client ID.
     *
     * @required
     *
     * @var string|null
     */
    private $clientId;

    /**
     * The challenge name. For more information, see InitiateAuth [^1].
     *
     * `ADMIN_NO_SRP_AUTH` isn't a valid value.
     *
     * [^1]: https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_InitiateAuth.html
     *
     * @required
     *
     * @var ChallengeNameType::*|null
     */
    private $challengeName;

    /**
     * The session that should be passed both ways in challenge-response calls to the service. If `InitiateAuth` or
     * `RespondToAuthChallenge` API call determines that the caller must pass another challenge, they return a session with
     * other challenge parameters. This session should be passed as it is to the next `RespondToAuthChallenge` API call.
     *
     * @var string|null
     */
    private $session;

    /**
     * The responses to the challenge that you received in the previous request. Each challenge has its own required
     * response parameters. The following examples are partial JSON request bodies that highlight challenge-response
     * parameters.
     *
     * ! You must provide a SECRET_HASH parameter in all challenge responses to an app client that has a client secret.
     * ! Include a `DEVICE_KEY` for device authentication.
     *
     * - `SELECT_CHALLENGE`:
     *
     *   `"ChallengeName": "SELECT_CHALLENGE", "ChallengeResponses": { "USERNAME": "[username]", "ANSWER": "[Challenge
     *   name]"}`
     *
     *   Available challenges are `PASSWORD`, `PASSWORD_SRP`, `EMAIL_OTP`, `SMS_OTP`, and `WEB_AUTHN`.
     *
     *   Complete authentication in the `SELECT_CHALLENGE` response for `PASSWORD`, `PASSWORD_SRP`, and `WEB_AUTHN`:
     *
     *   - `"ChallengeName": "SELECT_CHALLENGE", "ChallengeResponses": { "ANSWER": "WEB_AUTHN", "USERNAME": "[username]",
     *     "CREDENTIAL": "[AuthenticationResponseJSON]"}`
     *
     *     See AuthenticationResponseJSON [^1].
     *   - `"ChallengeName": "SELECT_CHALLENGE", "ChallengeResponses": { "ANSWER": "PASSWORD", "USERNAME": "[username]",
     *     "PASSWORD": "[password]"}`
     *   - `"ChallengeName": "SELECT_CHALLENGE", "ChallengeResponses": { "ANSWER": "PASSWORD_SRP", "USERNAME": "[username]",
     *     "SRP_A": "[SRP_A]"}`
     *
     *   For `SMS_OTP` and `EMAIL_OTP`, respond with the username and answer. Your user pool will send a code for the user
     *   to submit in the next challenge response.
     *
     *   - `"ChallengeName": "SELECT_CHALLENGE", "ChallengeResponses": { "ANSWER": "SMS_OTP", "USERNAME": "[username]"}`
     *   - `"ChallengeName": "SELECT_CHALLENGE", "ChallengeResponses": { "ANSWER": "EMAIL_OTP", "USERNAME": "[username]"}`
     *
     * - `SMS_OTP`:
     *
     *   `"ChallengeName": "SMS_OTP", "ChallengeResponses": {"SMS_OTP_CODE": "[code]", "USERNAME": "[username]"}`
     * - `EMAIL_OTP`:
     *
     *   `"ChallengeName": "EMAIL_OTP", "ChallengeResponses": {"EMAIL_OTP_CODE": "[code]", "USERNAME": "[username]"}`
     * - `SMS_MFA`:
     *
     *   `"ChallengeName": "SMS_MFA", "ChallengeResponses": {"SMS_MFA_CODE": "[code]", "USERNAME": "[username]"}`
     * - `PASSWORD_VERIFIER`:
     *
     *   This challenge response is part of the SRP flow. Amazon Cognito requires that your application respond to this
     *   challenge within a few seconds. When the response time exceeds this period, your user pool returns a
     *   `NotAuthorizedException` error.
     *
     *   `"ChallengeName": "PASSWORD_VERIFIER", "ChallengeResponses": {"PASSWORD_CLAIM_SIGNATURE": "[claim_signature]",
     *   "PASSWORD_CLAIM_SECRET_BLOCK": "[secret_block]", "TIMESTAMP": [timestamp], "USERNAME": "[username]"}`
     *
     *   Add `"DEVICE_KEY"` when you sign in with a remembered device.
     * - `CUSTOM_CHALLENGE`:
     *
     *   `"ChallengeName": "CUSTOM_CHALLENGE", "ChallengeResponses": {"USERNAME": "[username]", "ANSWER":
     *   "[challenge_answer]"}`
     *
     *   Add `"DEVICE_KEY"` when you sign in with a remembered device.
     * - `NEW_PASSWORD_REQUIRED`:
     *
     *   `"ChallengeName": "NEW_PASSWORD_REQUIRED", "ChallengeResponses": {"NEW_PASSWORD": "[new_password]", "USERNAME":
     *   "[username]"}`
     *
     *   To set any required attributes that `InitiateAuth` returned in an `requiredAttributes` parameter, add
     *   `"userAttributes.[attribute_name]": "[attribute_value]"`. This parameter can also set values for writable
     *   attributes that aren't required by your user pool.
     *
     *   > In a `NEW_PASSWORD_REQUIRED` challenge response, you can't modify a required attribute that already has a value.
     *   > In `RespondToAuthChallenge`, set a value for any keys that Amazon Cognito returned in the `requiredAttributes`
     *   > parameter, then use the `UpdateUserAttributes` API operation to modify the value of any additional attributes.
     *
     * - `SOFTWARE_TOKEN_MFA`:
     *
     *   `"ChallengeName": "SOFTWARE_TOKEN_MFA", "ChallengeResponses": {"USERNAME": "[username]", "SOFTWARE_TOKEN_MFA_CODE":
     *   [authenticator_code]}`
     * - `DEVICE_SRP_AUTH`:
     *
     *   `"ChallengeName": "DEVICE_SRP_AUTH", "ChallengeResponses": {"USERNAME": "[username]", "DEVICE_KEY": "[device_key]",
     *   "SRP_A": "[srp_a]"}`
     * - `DEVICE_PASSWORD_VERIFIER`:
     *
     *   `"ChallengeName": "DEVICE_PASSWORD_VERIFIER", "ChallengeResponses": {"DEVICE_KEY": "[device_key]",
     *   "PASSWORD_CLAIM_SIGNATURE": "[claim_signature]", "PASSWORD_CLAIM_SECRET_BLOCK": "[secret_block]", "TIMESTAMP":
     *   [timestamp], "USERNAME": "[username]"}`
     * - `MFA_SETUP`:
     *
     *   `"ChallengeName": "MFA_SETUP", "ChallengeResponses": {"USERNAME": "[username]"}, "SESSION": "[Session ID from
     *   VerifySoftwareToken]"`
     * - `SELECT_MFA_TYPE`:
     *
     *   `"ChallengeName": "SELECT_MFA_TYPE", "ChallengeResponses": {"USERNAME": "[username]", "ANSWER": "[SMS_MFA or
     *   SOFTWARE_TOKEN_MFA]"}`
     *
     * For more information about `SECRET_HASH`, see Computing secret hash values [^2]. For information about `DEVICE_KEY`,
     * see Working with user devices in your user pool [^3].
     *
     * [^1]: https://www.w3.org/TR/webauthn-3/#dictdef-authenticationresponsejson
     * [^2]: https://docs.aws.amazon.com/cognito/latest/developerguide/signing-up-users-in-your-app.html#cognito-user-pools-computing-secret-hash
     * [^3]: https://docs.aws.amazon.com/cognito/latest/developerguide/amazon-cognito-user-pools-device-tracking.html
     *
     * @var array<string, string>|null
     */
    private $challengeResponses;

    /**
     * The Amazon Pinpoint analytics metadata that contributes to your metrics for `RespondToAuthChallenge` calls.
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
     * A map of custom key-value pairs that you can provide as input for any custom workflows that this action triggers.
     *
     * You create custom workflows by assigning Lambda functions to user pool triggers. When you use the
     * RespondToAuthChallenge API action, Amazon Cognito invokes any functions that are assigned to the following triggers:
     * *post authentication*, *pre token generation*, *define auth challenge*, *create auth challenge*, and *verify auth
     * challenge*. When Amazon Cognito invokes any of these functions, it passes a JSON payload, which the function receives
     * as input. This payload contains a `clientMetadata` attribute, which provides the data that you assigned to the
     * ClientMetadata parameter in your RespondToAuthChallenge request. In your function code in Lambda, you can process the
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
     *   ClientId?: string,
     *   ChallengeName?: ChallengeNameType::*,
     *   Session?: null|string,
     *   ChallengeResponses?: null|array<string, string>,
     *   AnalyticsMetadata?: null|AnalyticsMetadataType|array,
     *   UserContextData?: null|UserContextDataType|array,
     *   ClientMetadata?: null|array<string, string>,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->clientId = $input['ClientId'] ?? null;
        $this->challengeName = $input['ChallengeName'] ?? null;
        $this->session = $input['Session'] ?? null;
        $this->challengeResponses = $input['ChallengeResponses'] ?? null;
        $this->analyticsMetadata = isset($input['AnalyticsMetadata']) ? AnalyticsMetadataType::create($input['AnalyticsMetadata']) : null;
        $this->userContextData = isset($input['UserContextData']) ? UserContextDataType::create($input['UserContextData']) : null;
        $this->clientMetadata = $input['ClientMetadata'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   ClientId?: string,
     *   ChallengeName?: ChallengeNameType::*,
     *   Session?: null|string,
     *   ChallengeResponses?: null|array<string, string>,
     *   AnalyticsMetadata?: null|AnalyticsMetadataType|array,
     *   UserContextData?: null|UserContextDataType|array,
     *   ClientMetadata?: null|array<string, string>,
     *   '@region'?: string|null,
     * }|RespondToAuthChallengeRequest $input
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
     * @return ChallengeNameType::*|null
     */
    public function getChallengeName(): ?string
    {
        return $this->challengeName;
    }

    /**
     * @return array<string, string>
     */
    public function getChallengeResponses(): array
    {
        return $this->challengeResponses ?? [];
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
            'X-Amz-Target' => 'AWSCognitoIdentityProviderService.RespondToAuthChallenge',
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
     * @param ChallengeNameType::*|null $value
     */
    public function setChallengeName(?string $value): self
    {
        $this->challengeName = $value;

        return $this;
    }

    /**
     * @param array<string, string> $value
     */
    public function setChallengeResponses(array $value): self
    {
        $this->challengeResponses = $value;

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
        if (null === $v = $this->clientId) {
            throw new InvalidArgument(\sprintf('Missing parameter "ClientId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['ClientId'] = $v;
        if (null === $v = $this->challengeName) {
            throw new InvalidArgument(\sprintf('Missing parameter "ChallengeName" for "%s". The value cannot be null.', __CLASS__));
        }
        if (!ChallengeNameType::exists($v)) {
            throw new InvalidArgument(\sprintf('Invalid parameter "ChallengeName" for "%s". The value "%s" is not a valid "ChallengeNameType".', __CLASS__, $v));
        }
        $payload['ChallengeName'] = $v;
        if (null !== $v = $this->session) {
            $payload['Session'] = $v;
        }
        if (null !== $v = $this->challengeResponses) {
            if (empty($v)) {
                $payload['ChallengeResponses'] = new \stdClass();
            } else {
                $payload['ChallengeResponses'] = [];
                foreach ($v as $name => $mv) {
                    $payload['ChallengeResponses'][$name] = $mv;
                }
            }
        }
        if (null !== $v = $this->analyticsMetadata) {
            $payload['AnalyticsMetadata'] = $v->requestBody();
        }
        if (null !== $v = $this->userContextData) {
            $payload['UserContextData'] = $v->requestBody();
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
