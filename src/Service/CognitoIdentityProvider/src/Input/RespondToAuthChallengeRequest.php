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
     * The challenge name. For more information, see InitiateAuth.
     *
     * @see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_InitiateAuth.html
     * @required
     *
     * @var null|ChallengeNameType::*
     */
    private $challengeName;

    /**
     * The session which should be passed both ways in challenge-response calls to the service. If `InitiateAuth` or
     * `RespondToAuthChallenge` API call determines that the caller needs to go through another challenge, they return a
     * session with other challenge parameters. This session should be passed as it is to the next `RespondToAuthChallenge`
     * API call.
     *
     * @var string|null
     */
    private $session;

    /**
     * The challenge responses. These are inputs corresponding to the value of `ChallengeName`, for example:.
     *
     * @var array<string, string>|null
     */
    private $challengeResponses;

    /**
     * The Amazon Pinpoint analytics metadata for collecting metrics for `RespondToAuthChallenge` calls.
     *
     * @var AnalyticsMetadataType|null
     */
    private $analyticsMetadata;

    /**
     * Contextual data such as the user's device fingerprint, IP address, or location used for evaluating the risk of an
     * unexpected event by Amazon Cognito advanced security.
     *
     * @var UserContextDataType|null
     */
    private $userContextData;

    /**
     * A map of custom key-value pairs that you can provide as input for any custom workflows that this action triggers.
     *
     * @var array<string, string>|null
     */
    private $clientMetadata;

    /**
     * @param array{
     *   ClientId?: string,
     *   ChallengeName?: ChallengeNameType::*,
     *   Session?: string,
     *   ChallengeResponses?: array<string, string>,
     *   AnalyticsMetadata?: AnalyticsMetadataType|array,
     *   UserContextData?: UserContextDataType|array,
     *   ClientMetadata?: array<string, string>,
     *   @region?: string,
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
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload);

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
