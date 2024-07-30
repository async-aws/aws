<?php

namespace AsyncAws\CognitoIdentityProvider\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class RevokeTokenRequest extends Input
{
    /**
     * The refresh token that you want to revoke.
     *
     * @required
     *
     * @var string|null
     */
    private $token;

    /**
     * The client ID for the token that you want to revoke.
     *
     * @required
     *
     * @var string|null
     */
    private $clientId;

    /**
     * The secret for the client ID. This is required only if the client ID has a secret.
     *
     * @var string|null
     */
    private $clientSecret;

    /**
     * @param array{
     *   Token?: string,
     *   ClientId?: string,
     *   ClientSecret?: null|string,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->token = $input['Token'] ?? null;
        $this->clientId = $input['ClientId'] ?? null;
        $this->clientSecret = $input['ClientSecret'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   Token?: string,
     *   ClientId?: string,
     *   ClientSecret?: null|string,
     *   '@region'?: string|null,
     * }|RevokeTokenRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getClientId(): ?string
    {
        return $this->clientId;
    }

    public function getClientSecret(): ?string
    {
        return $this->clientSecret;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.1',
            'X-Amz-Target' => 'AWSCognitoIdentityProviderService.RevokeToken',
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

    public function setClientId(?string $value): self
    {
        $this->clientId = $value;

        return $this;
    }

    public function setClientSecret(?string $value): self
    {
        $this->clientSecret = $value;

        return $this;
    }

    public function setToken(?string $value): self
    {
        $this->token = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->token) {
            throw new InvalidArgument(\sprintf('Missing parameter "Token" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Token'] = $v;
        if (null === $v = $this->clientId) {
            throw new InvalidArgument(\sprintf('Missing parameter "ClientId" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['ClientId'] = $v;
        if (null !== $v = $this->clientSecret) {
            $payload['ClientSecret'] = $v;
        }

        return $payload;
    }
}
