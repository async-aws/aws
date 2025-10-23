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
     * The ID of the app client where the user is signing in.
     *
     * @required
     *
     * @var string|null
     */
    private $clientId;

    /**
     * The name of the challenge that you are responding to.
     *
     * > You can't respond to an `ADMIN_NO_SRP_AUTH` challenge with this operation.
     *
     * Possible challenges include the following:
     *
     * > All of the following challenges require `USERNAME` and, when the app client has a client secret, `SECRET_HASH` in
     * > the parameters. Include a `DEVICE_KEY` for device authentication.
     *
     * - `WEB_AUTHN`: Respond to the challenge with the results of a successful authentication with a WebAuthn
     *   authenticator, or passkey, as `CREDENTIAL`. Examples of WebAuthn authenticators include biometric devices and
     *   security keys.
     * - `PASSWORD`: Respond with the user's password as `PASSWORD`.
     * - `PASSWORD_SRP`: Respond with the initial SRP secret as `SRP_A`.
     * - `SELECT_CHALLENGE`: Respond with a challenge selection as `ANSWER`. It must be one of the challenge types in the
     *   `AvailableChallenges` response parameter. Add the parameters of the selected challenge, for example `USERNAME` and
     *   `SMS_OTP`.
     * - `SMS_MFA`: Respond with the code that your user pool delivered in an SMS message, as `SMS_MFA_CODE`
     * - `EMAIL_MFA`: Respond with the code that your user pool delivered in an email message, as `EMAIL_MFA_CODE`
     * - `EMAIL_OTP`: Respond with the code that your user pool delivered in an email message, as `EMAIL_OTP_CODE` .
     * - `SMS_OTP`: Respond with the code that your user pool delivered in an SMS message, as `SMS_OTP_CODE`.
     * - `PASSWORD_VERIFIER`: Respond with the second stage of SRP secrets as `PASSWORD_CLAIM_SIGNATURE`,
     *   `PASSWORD_CLAIM_SECRET_BLOCK`, and `TIMESTAMP`.
     * - `CUSTOM_CHALLENGE`: This is returned if your custom authentication flow determines that the user should pass
     *   another challenge before tokens are issued. The parameters of the challenge are determined by your Lambda function
     *   and issued in the `ChallengeParameters` of a challenge response.
     * - `DEVICE_SRP_AUTH`: Respond with the initial parameters of device SRP authentication. For more information, see
     *   Signing in with a device [^1].
     * - `DEVICE_PASSWORD_VERIFIER`: Respond with `PASSWORD_CLAIM_SIGNATURE`, `PASSWORD_CLAIM_SECRET_BLOCK`, and `TIMESTAMP`
     *   after client-side SRP calculations. For more information, see Signing in with a device [^2].
     * - `NEW_PASSWORD_REQUIRED`: For users who are required to change their passwords after successful first login. Respond
     *   to this challenge with `NEW_PASSWORD` and any required attributes that Amazon Cognito returned in the
     *   `requiredAttributes` parameter. You can also set values for attributes that aren't required by your user pool and
     *   that your app client can write.
     *
     *   Amazon Cognito only returns this challenge for users who have temporary passwords. When you create passwordless
     *   users, you must provide values for all required attributes.
     *
     *   > In a `NEW_PASSWORD_REQUIRED` challenge response, you can't modify a required attribute that already has a value.
     *   > In `AdminRespondToAuthChallenge` or `RespondToAuthChallenge`, set a value for any keys that Amazon Cognito
     *   > returned in the `requiredAttributes` parameter, then use the `AdminUpdateUserAttributes` or
     *   > `UpdateUserAttributes` API operation to modify the value of any additional attributes.
     *
     * - `MFA_SETUP`: For users who are required to setup an MFA factor before they can sign in. The MFA types activated for
     *   the user pool will be listed in the challenge parameters `MFAS_CAN_SETUP` value.
     *
     *   To set up time-based one-time password (TOTP) MFA, use the session returned in this challenge from `InitiateAuth`
     *   or `AdminInitiateAuth` as an input to `AssociateSoftwareToken`. Then, use the session returned by
     *   `VerifySoftwareToken` as an input to `RespondToAuthChallenge` or `AdminRespondToAuthChallenge` with challenge name
     *   `MFA_SETUP` to complete sign-in.
     *
     *   To set up SMS or email MFA, collect a `phone_number` or `email` attribute for the user. Then restart the
     *   authentication flow with an `InitiateAuth` or `AdminInitiateAuth` request.
     *
     * [^1]: https://docs.aws.amazon.com/cognito/latest/developerguide/amazon-cognito-user-pools-device-tracking.html#user-pools-remembered-devices-signing-in-with-a-device
     * [^2]: https://docs.aws.amazon.com/cognito/latest/developerguide/amazon-cognito-user-pools-device-tracking.html#user-pools-remembered-devices-signing-in-with-a-device
     *
     * @required
     *
     * @var ChallengeNameType::*|null
     */
    private $challengeName;

    /**
     * The session identifier that maintains the state of authentication requests and challenge responses. If an
     * `AdminInitiateAuth` or `AdminRespondToAuthChallenge` API request results in a determination that your application
     * must pass another challenge, Amazon Cognito returns a session with other challenge parameters. Send this session
     * identifier, unmodified, to the next `AdminRespondToAuthChallenge` request.
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
     * - `WEB_AUTHN`:
     *
     *   `"ChallengeName": "WEB_AUTHN", "ChallengeResponses": { "USERNAME": "[username]", "CREDENTIAL":
     *   "[AuthenticationResponseJSON]"}`
     *
     *   See AuthenticationResponseJSON [^2].
     * - `PASSWORD`:
     *
     *   `"ChallengeName": "PASSWORD", "ChallengeResponses": { "USERNAME": "[username]", "PASSWORD": "[password]"}`
     * - `PASSWORD_SRP`:
     *
     *   `"ChallengeName": "PASSWORD_SRP", "ChallengeResponses": { "USERNAME": "[username]", "SRP_A": "[SRP_A]"}`
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
     * - `CUSTOM_CHALLENGE`:
     *
     *   `"ChallengeName": "CUSTOM_CHALLENGE", "ChallengeResponses": {"USERNAME": "[username]", "ANSWER":
     *   "[challenge_answer]"}`
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
     *   > In `AdminRespondToAuthChallenge` or `RespondToAuthChallenge`, set a value for any keys that Amazon Cognito
     *   > returned in the `requiredAttributes` parameter, then use the `AdminUpdateUserAttributes` or
     *   > `UpdateUserAttributes` API operation to modify the value of any additional attributes.
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
     *   `"ChallengeName": "SELECT_MFA_TYPE", "ChallengeResponses": {"USERNAME": "[username]", "ANSWER":
     *   "[SMS_MFA|EMAIL_MFA|SOFTWARE_TOKEN_MFA]"}`
     *
     * For more information about `SECRET_HASH`, see Computing secret hash values [^3]. For information about `DEVICE_KEY`,
     * see Working with user devices in your user pool [^4].
     *
     * [^1]: https://www.w3.org/TR/WebAuthn-3/#dictdef-authenticationresponsejson
     * [^2]: https://www.w3.org/TR/WebAuthn-3/#dictdef-authenticationresponsejson
     * [^3]: https://docs.aws.amazon.com/cognito/latest/developerguide/signing-up-users-in-your-app.html#cognito-user-pools-computing-secret-hash
     * [^4]: https://docs.aws.amazon.com/cognito/latest/developerguide/amazon-cognito-user-pools-device-tracking.html
     *
     * @var array<string, string>|null
     */
    private $challengeResponses;

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
     * @param array{
     *   ClientId?: string,
     *   ChallengeName?: ChallengeNameType::*,
     *   Session?: string|null,
     *   ChallengeResponses?: array<string, string>|null,
     *   AnalyticsMetadata?: AnalyticsMetadataType|array|null,
     *   UserContextData?: UserContextDataType|array|null,
     *   ClientMetadata?: array<string, string>|null,
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
     *   Session?: string|null,
     *   ChallengeResponses?: array<string, string>|null,
     *   AnalyticsMetadata?: AnalyticsMetadataType|array|null,
     *   UserContextData?: UserContextDataType|array|null,
     *   ClientMetadata?: array<string, string>|null,
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
