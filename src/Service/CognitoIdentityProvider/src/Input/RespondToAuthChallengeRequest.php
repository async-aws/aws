<?php

namespace AsyncAws\CognitoIdentityProvider\Input;

use AsyncAws\CognitoIdentityProvider\Enum\ChallengeNameType;
use AsyncAws\CognitoIdentityProvider\ValueObject\AnalyticsMetadataType;
use AsyncAws\CognitoIdentityProvider\ValueObject\UserContextDataType;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class RespondToAuthChallengeRequest extends Input
{
    /**
     * The app client ID.
     *
     * @required
     *
     * @var string|null
     */
    private $ClientId;

    /**
     * The challenge name. For more information, see .
     *
     * @required
     *
     * @var null|ChallengeNameType::*
     */
    private $ChallengeName;

    /**
     * The session which should be passed both ways in challenge-response calls to the service. If `InitiateAuth` or
     * `RespondToAuthChallenge` API call determines that the caller needs to go through another challenge, they return a
     * session with other challenge parameters. This session should be passed as it is to the next `RespondToAuthChallenge`
     * API call.
     *
     * @var string|null
     */
    private $Session;

    /**
     * The challenge responses. These are inputs corresponding to the value of `ChallengeName`, for example:.
     *
     * @var array<string, string>|null
     */
    private $ChallengeResponses;

    /**
     * The Amazon Pinpoint analytics metadata for collecting metrics for `RespondToAuthChallenge` calls.
     *
     * @var AnalyticsMetadataType|null
     */
    private $AnalyticsMetadata;

    /**
     * Contextual data such as the user's device fingerprint, IP address, or location used for evaluating the risk of an
     * unexpected event by Amazon Cognito advanced security.
     *
     * @var UserContextDataType|null
     */
    private $UserContextData;

    /**
     * A map of custom key-value pairs that you can provide as input for any custom workflows that this action triggers.
     *
     * @var array<string, string>|null
     */
    private $ClientMetadata;

    /**
     * @param array{
     *   ClientId?: string,
     *   ChallengeName?: \AsyncAws\CognitoIdentityProvider\Enum\ChallengeNameType::*,
     *   Session?: string,
     *   ChallengeResponses?: array<string, string>,
     *   AnalyticsMetadata?: \AsyncAws\CognitoIdentityProvider\ValueObject\AnalyticsMetadataType|array,
     *   UserContextData?: \AsyncAws\CognitoIdentityProvider\ValueObject\UserContextDataType|array,
     *   ClientMetadata?: array<string, string>,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->ClientId = $input['ClientId'] ?? null;
        $this->ChallengeName = $input['ChallengeName'] ?? null;
        $this->Session = $input['Session'] ?? null;
        $this->ChallengeResponses = $input['ChallengeResponses'] ?? null;
        $this->AnalyticsMetadata = isset($input['AnalyticsMetadata']) ? AnalyticsMetadataType::create($input['AnalyticsMetadata']) : null;
        $this->UserContextData = isset($input['UserContextData']) ? UserContextDataType::create($input['UserContextData']) : null;
        $this->ClientMetadata = $input['ClientMetadata'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAnalyticsMetadata(): ?AnalyticsMetadataType
    {
        return $this->AnalyticsMetadata;
    }

    /**
     * @return ChallengeNameType::*|null
     */
    public function getChallengeName(): ?string
    {
        return $this->ChallengeName;
    }

    /**
     * @return array<string, string>
     */
    public function getChallengeResponses(): array
    {
        return $this->ChallengeResponses ?? [];
    }

    public function getClientId(): ?string
    {
        return $this->ClientId;
    }

    /**
     * @return array<string, string>
     */
    public function getClientMetadata(): array
    {
        return $this->ClientMetadata ?? [];
    }

    public function getSession(): ?string
    {
        return $this->Session;
    }

    public function getUserContextData(): ?UserContextDataType
    {
        return $this->UserContextData;
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
        $this->AnalyticsMetadata = $value;

        return $this;
    }

    /**
     * @param ChallengeNameType::*|null $value
     */
    public function setChallengeName(?string $value): self
    {
        $this->ChallengeName = $value;

        return $this;
    }

    /**
     * @param array<string, string> $value
     */
    public function setChallengeResponses(array $value): self
    {
        $this->ChallengeResponses = $value;

        return $this;
    }

    public function setClientId(?string $value): self
    {
        $this->ClientId = $value;

        return $this;
    }

    /**
     * @param array<string, string> $value
     */
    public function setClientMetadata(array $value): self
    {
        $this->ClientMetadata = $value;

        return $this;
    }

    public function setSession(?string $value): self
    {
        $this->Session = $value;

        return $this;
    }

    public function setUserContextData(?UserContextDataType $value): self
    {
        $this->UserContextData = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->ClientId) {
            throw new InvalidArgument(sprintf('Missing parameter "ClientId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['ClientId'] = $v;
        if (null === $v = $this->ChallengeName) {
            throw new InvalidArgument(sprintf('Missing parameter "ChallengeName" for "%s". The value cannot be null.', __CLASS__));
        }
        if (!ChallengeNameType::exists($v)) {
            throw new InvalidArgument(sprintf('Invalid parameter "ChallengeName" for "%s". The value "%s" is not a valid "ChallengeNameType".', __CLASS__, $v));
        }
        $payload['ChallengeName'] = $v;
        if (null !== $v = $this->Session) {
            $payload['Session'] = $v;
        }
        if (null !== $v = $this->ChallengeResponses) {
            foreach ($v as $name => $v) {
                $payload['ChallengeResponses'][$name] = $v;
            }
        }
        if (null !== $v = $this->AnalyticsMetadata) {
            $payload['AnalyticsMetadata'] = $v->requestBody();
        }
        if (null !== $v = $this->UserContextData) {
            $payload['UserContextData'] = $v->requestBody();
        }
        if (null !== $v = $this->ClientMetadata) {
            foreach ($v as $name => $v) {
                $payload['ClientMetadata'][$name] = $v;
            }
        }

        return $payload;
    }
}
