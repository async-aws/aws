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
     * The challenge responses. These are inputs corresponding to the value of `ChallengeName`, for example:.
     *
     * > `SECRET_HASH` (if app client is configured with client secret) applies to all of the inputs that follow (including
     * > `SOFTWARE_TOKEN_MFA`).
     *
     * - `SMS_MFA`: `SMS_MFA_CODE`, `USERNAME`.
     * - `PASSWORD_VERIFIER`: `PASSWORD_CLAIM_SIGNATURE`, `PASSWORD_CLAIM_SECRET_BLOCK`, `TIMESTAMP`, `USERNAME`.
     *
     *   > `PASSWORD_VERIFIER` requires `DEVICE_KEY` when you sign in with a remembered device.
     *
     * - `NEW_PASSWORD_REQUIRED`: `NEW_PASSWORD`, `USERNAME`, `SECRET_HASH` (if app client is configured with client
     *   secret). To set any required attributes that Amazon Cognito returned as `requiredAttributes` in the `InitiateAuth`
     *   response, add a `userAttributes.*attributename*` parameter. This parameter can also set values for writable
     *   attributes that aren't required by your user pool.
     *
     *   > In a `NEW_PASSWORD_REQUIRED` challenge response, you can't modify a required attribute that already has a value.
     *   > In `RespondToAuthChallenge`, set a value for any keys that Amazon Cognito returned in the `requiredAttributes`
     *   > parameter, then use the `UpdateUserAttributes` API operation to modify the value of any additional attributes.
     *
     * - `SOFTWARE_TOKEN_MFA`: `USERNAME` and `SOFTWARE_TOKEN_MFA_CODE` are required attributes.
     * - `DEVICE_SRP_AUTH` requires `USERNAME`, `DEVICE_KEY`, `SRP_A` (and `SECRET_HASH`).
     * - `DEVICE_PASSWORD_VERIFIER` requires everything that `PASSWORD_VERIFIER` requires, plus `DEVICE_KEY`.
     * - `MFA_SETUP` requires `USERNAME`, plus you must use the session value returned by `VerifySoftwareToken` in the
     *   `Session` parameter.
     *
     * For more information about `SECRET_HASH`, see Computing secret hash values [^1]. For information about `DEVICE_KEY`,
     * see Working with user devices in your user pool [^2].
     *
     * [^1]: https://docs.aws.amazon.com/cognito/latest/developerguide/signing-up-users-in-your-app.html#cognito-user-pools-computing-secret-hash
     * [^2]: https://docs.aws.amazon.com/cognito/latest/developerguide/amazon-cognito-user-pools-device-tracking.html
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
            throw new InvalidArgument(sprintf('Missing parameter "ClientId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['ClientId'] = $v;
        if (null === $v = $this->challengeName) {
            throw new InvalidArgument(sprintf('Missing parameter "ChallengeName" for "%s". The value cannot be null.', __CLASS__));
        }
        if (!ChallengeNameType::exists($v)) {
            throw new InvalidArgument(sprintf('Invalid parameter "ChallengeName" for "%s". The value "%s" is not a valid "ChallengeNameType".', __CLASS__, $v));
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
