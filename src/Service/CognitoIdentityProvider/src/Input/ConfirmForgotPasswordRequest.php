<?php

namespace AsyncAws\CognitoIdentityProvider\Input;

use AsyncAws\CognitoIdentityProvider\ValueObject\AnalyticsMetadataType;
use AsyncAws\CognitoIdentityProvider\ValueObject\UserContextDataType;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class ConfirmForgotPasswordRequest extends Input
{
    /**
     * The app client ID of the app associated with the user pool.
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
     * The user name of the user for whom you want to enter a code to retrieve a forgotten password.
     *
     * @required
     *
     * @var string|null
     */
    private $Username;

    /**
     * The confirmation code sent by a user's request to retrieve a forgotten password. For more information, see.
     *
     * @required
     *
     * @var string|null
     */
    private $ConfirmationCode;

    /**
     * The password sent by a user's request to retrieve a forgotten password.
     *
     * @required
     *
     * @var string|null
     */
    private $Password;

    /**
     * The Amazon Pinpoint analytics metadata for collecting metrics for `ConfirmForgotPassword` calls.
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
     *   ConfirmationCode?: string,
     *   Password?: string,
     *   AnalyticsMetadata?: \AsyncAws\CognitoIdentityProvider\ValueObject\AnalyticsMetadataType|array,
     *   UserContextData?: \AsyncAws\CognitoIdentityProvider\ValueObject\UserContextDataType|array,
     *   ClientMetadata?: array<string, string>,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->ClientId = $input['ClientId'] ?? null;
        $this->SecretHash = $input['SecretHash'] ?? null;
        $this->Username = $input['Username'] ?? null;
        $this->ConfirmationCode = $input['ConfirmationCode'] ?? null;
        $this->Password = $input['Password'] ?? null;
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

    public function getConfirmationCode(): ?string
    {
        return $this->ConfirmationCode;
    }

    public function getPassword(): ?string
    {
        return $this->Password;
    }

    public function getSecretHash(): ?string
    {
        return $this->SecretHash;
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
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'AWSCognitoIdentityProviderService.ConfirmForgotPassword',
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

    public function setConfirmationCode(?string $value): self
    {
        $this->ConfirmationCode = $value;

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
        if (null === $v = $this->ConfirmationCode) {
            throw new InvalidArgument(sprintf('Missing parameter "ConfirmationCode" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['ConfirmationCode'] = $v;
        if (null === $v = $this->Password) {
            throw new InvalidArgument(sprintf('Missing parameter "Password" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Password'] = $v;
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
