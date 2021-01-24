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
     * The authentication flow for this call to execute. The API action will depend on this value. For example:.
     *
     * @required
     *
     * @var null|AuthFlowType::*
     */
    private $AuthFlow;

    /**
     * The authentication parameters. These are inputs corresponding to the `AuthFlow` that you are invoking. The required
     * values depend on the value of `AuthFlow`:.
     *
     * @var array<string, string>|null
     */
    private $AuthParameters;

    /**
     * A map of custom key-value pairs that you can provide as input for certain custom workflows that this action triggers.
     *
     * @var array<string, string>|null
     */
    private $ClientMetadata;

    /**
     * The app client ID.
     *
     * @required
     *
     * @var string|null
     */
    private $ClientId;

    /**
     * The Amazon Pinpoint analytics metadata for collecting metrics for `InitiateAuth` calls.
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
     * @param array{
     *   AuthFlow?: AuthFlowType::*,
     *   AuthParameters?: array<string, string>,
     *   ClientMetadata?: array<string, string>,
     *   ClientId?: string,
     *   AnalyticsMetadata?: AnalyticsMetadataType|array,
     *   UserContextData?: UserContextDataType|array,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->AuthFlow = $input['AuthFlow'] ?? null;
        $this->AuthParameters = $input['AuthParameters'] ?? null;
        $this->ClientMetadata = $input['ClientMetadata'] ?? null;
        $this->ClientId = $input['ClientId'] ?? null;
        $this->AnalyticsMetadata = isset($input['AnalyticsMetadata']) ? AnalyticsMetadataType::create($input['AnalyticsMetadata']) : null;
        $this->UserContextData = isset($input['UserContextData']) ? UserContextDataType::create($input['UserContextData']) : null;
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
     * @return AuthFlowType::*|null
     */
    public function getAuthFlow(): ?string
    {
        return $this->AuthFlow;
    }

    /**
     * @return array<string, string>
     */
    public function getAuthParameters(): array
    {
        return $this->AuthParameters ?? [];
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
            'X-Amz-Target' => 'AWSCognitoIdentityProviderService.InitiateAuth',
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
     * @param AuthFlowType::*|null $value
     */
    public function setAuthFlow(?string $value): self
    {
        $this->AuthFlow = $value;

        return $this;
    }

    /**
     * @param array<string, string> $value
     */
    public function setAuthParameters(array $value): self
    {
        $this->AuthParameters = $value;

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

    public function setUserContextData(?UserContextDataType $value): self
    {
        $this->UserContextData = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->AuthFlow) {
            throw new InvalidArgument(sprintf('Missing parameter "AuthFlow" for "%s". The value cannot be null.', __CLASS__));
        }
        if (!AuthFlowType::exists($v)) {
            throw new InvalidArgument(sprintf('Invalid parameter "AuthFlow" for "%s". The value "%s" is not a valid "AuthFlowType".', __CLASS__, $v));
        }
        $payload['AuthFlow'] = $v;
        if (null !== $v = $this->AuthParameters) {
            if (empty($v)) {
                $payload['AuthParameters'] = new \stdClass();
            } else {
                $payload['AuthParameters'] = [];
                foreach ($v as $name => $mv) {
                    $payload['AuthParameters'][$name] = $mv;
                }
            }
        }
        if (null !== $v = $this->ClientMetadata) {
            if (empty($v)) {
                $payload['ClientMetadata'] = new \stdClass();
            } else {
                $payload['ClientMetadata'] = [];
                foreach ($v as $name => $mv) {
                    $payload['ClientMetadata'][$name] = $mv;
                }
            }
        }
        if (null === $v = $this->ClientId) {
            throw new InvalidArgument(sprintf('Missing parameter "ClientId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['ClientId'] = $v;
        if (null !== $v = $this->AnalyticsMetadata) {
            $payload['AnalyticsMetadata'] = $v->requestBody();
        }
        if (null !== $v = $this->UserContextData) {
            $payload['UserContextData'] = $v->requestBody();
        }

        return $payload;
    }
}
