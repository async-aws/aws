<?php

namespace AsyncAws\CognitoIdentityProvider\Input;

use AsyncAws\CognitoIdentityProvider\ValueObject\AnalyticsMetadataType;
use AsyncAws\CognitoIdentityProvider\ValueObject\UserContextDataType;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * The request representing the confirmation for a password reset.
 */
final class ConfirmForgotPasswordRequest extends Input
{
    /**
     * The app client ID of the app associated with the user pool.
     *
     * @required
     *
     * @var string|null
     */
    private $clientId;

    /**
     * A keyed-hash message authentication code (HMAC) calculated using the secret key of a user pool client and username
     * plus the client ID in the message.
     *
     * @var string|null
     */
    private $secretHash;

    /**
     * The user name of the user for whom you want to enter a code to retrieve a forgotten password.
     *
     * @required
     *
     * @var string|null
     */
    private $username;

    /**
     * The confirmation code sent by a user's request to retrieve a forgotten password. For more information, see
     * ForgotPassword.
     *
     * @see https://docs.aws.amazon.com/cognito-user-identity-pools/latest/APIReference/API_ForgotPassword.html
     * @required
     *
     * @var string|null
     */
    private $confirmationCode;

    /**
     * The password sent by a user's request to retrieve a forgotten password.
     *
     * @required
     *
     * @var string|null
     */
    private $password;

    /**
     * The Amazon Pinpoint analytics metadata for collecting metrics for `ConfirmForgotPassword` calls.
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
     *   SecretHash?: string,
     *   Username?: string,
     *   ConfirmationCode?: string,
     *   Password?: string,
     *   AnalyticsMetadata?: AnalyticsMetadataType|array,
     *   UserContextData?: UserContextDataType|array,
     *   ClientMetadata?: array<string, string>,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->clientId = $input['ClientId'] ?? null;
        $this->secretHash = $input['SecretHash'] ?? null;
        $this->username = $input['Username'] ?? null;
        $this->confirmationCode = $input['ConfirmationCode'] ?? null;
        $this->password = $input['Password'] ?? null;
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

    public function getConfirmationCode(): ?string
    {
        return $this->confirmationCode;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getSecretHash(): ?string
    {
        return $this->secretHash;
    }

    public function getUserContextData(): ?UserContextDataType
    {
        return $this->userContextData;
    }

    public function getUsername(): ?string
    {
        return $this->username;
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
        $body = empty($bodyPayload) ? '{}' : \json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setAnalyticsMetadata(?AnalyticsMetadataType $value): self
    {
        $this->analyticsMetadata = $value;

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

    public function setConfirmationCode(?string $value): self
    {
        $this->confirmationCode = $value;

        return $this;
    }

    public function setPassword(?string $value): self
    {
        $this->password = $value;

        return $this;
    }

    public function setSecretHash(?string $value): self
    {
        $this->secretHash = $value;

        return $this;
    }

    public function setUserContextData(?UserContextDataType $value): self
    {
        $this->userContextData = $value;

        return $this;
    }

    public function setUsername(?string $value): self
    {
        $this->username = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->clientId) {
            throw new InvalidArgument(sprintf('Missing parameter "ClientId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['ClientId'] = $v;
        if (null !== $v = $this->secretHash) {
            $payload['SecretHash'] = $v;
        }
        if (null === $v = $this->username) {
            throw new InvalidArgument(sprintf('Missing parameter "Username" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Username'] = $v;
        if (null === $v = $this->confirmationCode) {
            throw new InvalidArgument(sprintf('Missing parameter "ConfirmationCode" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['ConfirmationCode'] = $v;
        if (null === $v = $this->password) {
            throw new InvalidArgument(sprintf('Missing parameter "Password" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Password'] = $v;
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
