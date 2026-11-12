<?php

namespace AsyncAws\CognitoIdentityProvider\Input;

use AsyncAws\CognitoIdentityProvider\Enum\RevokeAssociatedSessionsType;
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
     * The ID of the app client where the token that you want to revoke was issued.
     *
     * @required
     *
     * @var string|null
     */
    private $clientId;

    /**
     * The client secret of the requested app client, if the client has a secret.
     *
     * @var string|null
     */
    private $clientSecret;

    /**
     * Specifies whether to also revoke the SSO session that is linked to the token session being revoked. `NONE`, the
     * default, revokes only the token session that is associated with the refresh token. `LINKED` also revokes the parent
     * SSO session that the token session came through; the other token sessions that came through that SSO session aren't
     * revoked. `LINKED` has no effect when session tracking is disabled or when the token session has no parent SSO
     * session.
     *
     * @var RevokeAssociatedSessionsType::*|null
     */
    private $revokeAssociatedSessions;

    /**
     * @param array{
     *   Token?: string,
     *   ClientId?: string,
     *   ClientSecret?: string|null,
     *   RevokeAssociatedSessions?: RevokeAssociatedSessionsType::*|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->token = $input['Token'] ?? null;
        $this->clientId = $input['ClientId'] ?? null;
        $this->clientSecret = $input['ClientSecret'] ?? null;
        $this->revokeAssociatedSessions = $input['RevokeAssociatedSessions'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   Token?: string,
     *   ClientId?: string,
     *   ClientSecret?: string|null,
     *   RevokeAssociatedSessions?: RevokeAssociatedSessionsType::*|null,
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

    /**
     * @return RevokeAssociatedSessionsType::*|null
     */
    public function getRevokeAssociatedSessions(): ?string
    {
        return $this->revokeAssociatedSessions;
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

    /**
     * @param RevokeAssociatedSessionsType::*|null $value
     */
    public function setRevokeAssociatedSessions(?string $value): self
    {
        $this->revokeAssociatedSessions = $value;

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
        if (null !== $v = $this->revokeAssociatedSessions) {
            if (!RevokeAssociatedSessionsType::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "RevokeAssociatedSessions" for "%s". The value "%s" is not a valid "RevokeAssociatedSessionsType".', __CLASS__, $v));
            }
            $payload['RevokeAssociatedSessions'] = $v;
        }

        return $payload;
    }
}
