<?php

namespace AsyncAws\SsoOidc\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class RegisterClientResponse extends Result
{
    /**
     * The unique identifier string for each client. This client uses this identifier to get authenticated by the service in
     * subsequent calls.
     *
     * @var string|null
     */
    private $clientId;

    /**
     * A secret string generated for the client. The client will use this string to get authenticated by the service in
     * subsequent calls.
     *
     * @var string|null
     */
    private $clientSecret;

    /**
     * Indicates the time at which the `clientId` and `clientSecret` were issued.
     *
     * @var int|null
     */
    private $clientIdIssuedAt;

    /**
     * Indicates the time at which the `clientId` and `clientSecret` will become invalid.
     *
     * @var int|null
     */
    private $clientSecretExpiresAt;

    /**
     * An endpoint that the client can use to request authorization.
     *
     * @var string|null
     */
    private $authorizationEndpoint;

    /**
     * An endpoint that the client can use to create tokens.
     *
     * @var string|null
     */
    private $tokenEndpoint;

    public function getAuthorizationEndpoint(): ?string
    {
        $this->initialize();

        return $this->authorizationEndpoint;
    }

    public function getClientId(): ?string
    {
        $this->initialize();

        return $this->clientId;
    }

    public function getClientIdIssuedAt(): ?int
    {
        $this->initialize();

        return $this->clientIdIssuedAt;
    }

    public function getClientSecret(): ?string
    {
        $this->initialize();

        return $this->clientSecret;
    }

    public function getClientSecretExpiresAt(): ?int
    {
        $this->initialize();

        return $this->clientSecretExpiresAt;
    }

    public function getTokenEndpoint(): ?string
    {
        $this->initialize();

        return $this->tokenEndpoint;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->clientId = isset($data['clientId']) ? (string) $data['clientId'] : null;
        $this->clientSecret = isset($data['clientSecret']) ? (string) $data['clientSecret'] : null;
        $this->clientIdIssuedAt = isset($data['clientIdIssuedAt']) ? (int) $data['clientIdIssuedAt'] : null;
        $this->clientSecretExpiresAt = isset($data['clientSecretExpiresAt']) ? (int) $data['clientSecretExpiresAt'] : null;
        $this->authorizationEndpoint = isset($data['authorizationEndpoint']) ? (string) $data['authorizationEndpoint'] : null;
        $this->tokenEndpoint = isset($data['tokenEndpoint']) ? (string) $data['tokenEndpoint'] : null;
    }
}
