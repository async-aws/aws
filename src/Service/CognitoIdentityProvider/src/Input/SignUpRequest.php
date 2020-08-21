<?php

namespace AsyncAws\CognitoIdentityProvider\Input;

use AsyncAws\CognitoIdentityProvider\ValueObject\AnalyticsMetadataType;
use AsyncAws\CognitoIdentityProvider\ValueObject\AttributeType;
use AsyncAws\CognitoIdentityProvider\ValueObject\UserContextDataType;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class SignUpRequest extends Input
{
    /**
     * The ID of the client associated with the user pool.
     *
     * @required
     *
     * @var string|null
     */
    private $ClientId;

    /**
     * A keyed-hash message authentication code (HMAC) calculated using the secret key of a user pool client and username
     * plus the client ID in the message.
     *
     * @var string|null
     */
    private $SecretHash;

    /**
     * The user name of the user you wish to register.
     *
     * @required
     *
     * @var string|null
     */
    private $Username;

    /**
     * The password of the user you wish to register.
     *
     * @required
     *
     * @var string|null
     */
    private $Password;

    /**
     * An array of name-value pairs representing user attributes.
     *
     * @var AttributeType[]|null
     */
    private $UserAttributes;

    /**
     * The validation data in the request to register a user.
     *
     * @var AttributeType[]|null
     */
    private $ValidationData;

    /**
     * The Amazon Pinpoint analytics metadata for collecting metrics for `SignUp` calls.
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
     *   SecretHash?: string,
     *   Username?: string,
     *   Password?: string,
     *   UserAttributes?: AttributeType[],
     *   ValidationData?: AttributeType[],
     *   AnalyticsMetadata?: AnalyticsMetadataType|array,
     *   UserContextData?: UserContextDataType|array,
     *   ClientMetadata?: array<string, string>,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->ClientId = $input['ClientId'] ?? null;
        $this->SecretHash = $input['SecretHash'] ?? null;
        $this->Username = $input['Username'] ?? null;
        $this->Password = $input['Password'] ?? null;
        $this->UserAttributes = isset($input['UserAttributes']) ? array_map([AttributeType::class, 'create'], $input['UserAttributes']) : null;
        $this->ValidationData = isset($input['ValidationData']) ? array_map([AttributeType::class, 'create'], $input['ValidationData']) : null;
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

    public function getPassword(): ?string
    {
        return $this->Password;
    }

    public function getSecretHash(): ?string
    {
        return $this->SecretHash;
    }

    /**
     * @return AttributeType[]
     */
    public function getUserAttributes(): array
    {
        return $this->UserAttributes ?? [];
    }

    public function getUserContextData(): ?UserContextDataType
    {
        return $this->UserContextData;
    }

    public function getUsername(): ?string
    {
        return $this->Username;
    }

    /**
     * @return AttributeType[]
     */
    public function getValidationData(): array
    {
        return $this->ValidationData ?? [];
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'AWSCognitoIdentityProviderService.SignUp',
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

    public function setPassword(?string $value): self
    {
        $this->Password = $value;

        return $this;
    }

    public function setSecretHash(?string $value): self
    {
        $this->SecretHash = $value;

        return $this;
    }

    /**
     * @param AttributeType[] $value
     */
    public function setUserAttributes(array $value): self
    {
        $this->UserAttributes = $value;

        return $this;
    }

    public function setUserContextData(?UserContextDataType $value): self
    {
        $this->UserContextData = $value;

        return $this;
    }

    public function setUsername(?string $value): self
    {
        $this->Username = $value;

        return $this;
    }

    /**
     * @param AttributeType[] $value
     */
    public function setValidationData(array $value): self
    {
        $this->ValidationData = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->ClientId) {
            throw new InvalidArgument(sprintf('Missing parameter "ClientId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['ClientId'] = $v;
        if (null !== $v = $this->SecretHash) {
            $payload['SecretHash'] = $v;
        }
        if (null === $v = $this->Username) {
            throw new InvalidArgument(sprintf('Missing parameter "Username" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Username'] = $v;
        if (null === $v = $this->Password) {
            throw new InvalidArgument(sprintf('Missing parameter "Password" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Password'] = $v;
        if (null !== $v = $this->UserAttributes) {
            $index = -1;
            $payload['UserAttributes'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['UserAttributes'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->ValidationData) {
            $index = -1;
            $payload['ValidationData'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['ValidationData'][$index] = $listValue->requestBody();
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
