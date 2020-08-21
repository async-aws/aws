<?php

namespace AsyncAws\CognitoIdentityProvider\Input;

use AsyncAws\CognitoIdentityProvider\Enum\AuthFlowType;
use AsyncAws\CognitoIdentityProvider\ValueObject\AnalyticsMetadataType;
use AsyncAws\CognitoIdentityProvider\ValueObject\ContextDataType;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class AdminInitiateAuthRequest extends Input
{
    /**
     * The ID of the Amazon Cognito user pool.
     *
     * @required
     *
     * @var string|null
     */
    private $UserPoolId;

    /**
     * The app client ID.
     *
     * @required
     *
     * @var string|null
     */
    private $ClientId;

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
     * The analytics metadata for collecting Amazon Pinpoint metrics for `AdminInitiateAuth` calls.
     *
     * @var AnalyticsMetadataType|null
     */
    private $AnalyticsMetadata;

    /**
     * Contextual data such as the user's device fingerprint, IP address, or location used for evaluating the risk of an
     * unexpected event by Amazon Cognito advanced security.
     *
     * @var ContextDataType|null
     */
    private $ContextData;

    /**
     * @param array{
     *   UserPoolId?: string,
     *   ClientId?: string,
     *   AuthFlow?: AuthFlowType::*,
     *   AuthParameters?: array<string, string>,
     *   ClientMetadata?: array<string, string>,
     *   AnalyticsMetadata?: AnalyticsMetadataType|array,
     *   ContextData?: ContextDataType|array,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->UserPoolId = $input['UserPoolId'] ?? null;
        $this->ClientId = $input['ClientId'] ?? null;
        $this->AuthFlow = $input['AuthFlow'] ?? null;
        $this->AuthParameters = $input['AuthParameters'] ?? null;
        $this->ClientMetadata = $input['ClientMetadata'] ?? null;
        $this->AnalyticsMetadata = isset($input['AnalyticsMetadata']) ? AnalyticsMetadataType::create($input['AnalyticsMetadata']) : null;
        $this->ContextData = isset($input['ContextData']) ? ContextDataType::create($input['ContextData']) : null;
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

    public function getContextData(): ?ContextDataType
    {
        return $this->ContextData;
    }

    public function getUserPoolId(): ?string
    {
        return $this->UserPoolId;
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

    public function setContextData(?ContextDataType $value): self
    {
        $this->ContextData = $value;

        return $this;
    }

    public function setUserPoolId(?string $value): self
    {
        $this->UserPoolId = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->UserPoolId) {
            throw new InvalidArgument(sprintf('Missing parameter "UserPoolId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['UserPoolId'] = $v;
        if (null === $v = $this->ClientId) {
            throw new InvalidArgument(sprintf('Missing parameter "ClientId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['ClientId'] = $v;
        if (null === $v = $this->AuthFlow) {
            throw new InvalidArgument(sprintf('Missing parameter "AuthFlow" for "%s". The value cannot be null.', __CLASS__));
        }
        if (!AuthFlowType::exists($v)) {
            throw new InvalidArgument(sprintf('Invalid parameter "AuthFlow" for "%s". The value "%s" is not a valid "AuthFlowType".', __CLASS__, $v));
        }
        $payload['AuthFlow'] = $v;
        if (null !== $v = $this->AuthParameters) {
            foreach ($v as $name => $v) {
                $payload['AuthParameters'][$name] = $v;
            }
        }
        if (null !== $v = $this->ClientMetadata) {
            foreach ($v as $name => $v) {
                $payload['ClientMetadata'][$name] = $v;
            }
        }
        if (null !== $v = $this->AnalyticsMetadata) {
            $payload['AnalyticsMetadata'] = $v->requestBody();
        }
        if (null !== $v = $this->ContextData) {
            $payload['ContextData'] = $v->requestBody();
        }

        return $payload;
    }
}
