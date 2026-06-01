<?php

namespace AsyncAws\SsoOidc\Input;

use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class RegisterClientRequest extends Input
{
    /**
     * The friendly name of the client.
     *
     * @required
     *
     * @var string|null
     */
    private $clientName;

    /**
     * The type of client. The service supports only `public` as a client type. Anything other than public will be rejected
     * by the service.
     *
     * @required
     *
     * @var string|null
     */
    private $clientType;

    /**
     * The list of scopes that are defined by the client. Upon authorization, this list is used to restrict permissions when
     * granting an access token.
     *
     * @var string[]|null
     */
    private $scopes;

    /**
     * The list of redirect URI that are defined by the client. At completion of authorization, this list is used to
     * restrict what locations the user agent can be redirected back to.
     *
     * @var string[]|null
     */
    private $redirectUris;

    /**
     * The list of OAuth 2.0 grant types that are defined by the client. This list is used to restrict the token granting
     * flows available to the client. Supports the following OAuth 2.0 grant types: Authorization Code, Device Code, and
     * Refresh Token.
     *
     * * Authorization Code - `authorization_code`
     *
     * * Device Code - `urn:ietf:params:oauth:grant-type:device_code`
     *
     * * Refresh Token - `refresh_token`
     *
     * @var string[]|null
     */
    private $grantTypes;

    /**
     * The IAM Identity Center Issuer URL associated with an instance of IAM Identity Center. This value is needed for user
     * access to resources through the client.
     *
     * @var string|null
     */
    private $issuerUrl;

    /**
     * This IAM Identity Center application ARN is used to define administrator-managed configuration for public client
     * access to resources. At authorization, the scopes, grants, and redirect URI available to this client will be
     * restricted by this application resource.
     *
     * @var string|null
     */
    private $entitledApplicationArn;

    /**
     * @param array{
     *   clientName?: string,
     *   clientType?: string,
     *   scopes?: string[]|null,
     *   redirectUris?: string[]|null,
     *   grantTypes?: string[]|null,
     *   issuerUrl?: string|null,
     *   entitledApplicationArn?: string|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->clientName = $input['clientName'] ?? null;
        $this->clientType = $input['clientType'] ?? null;
        $this->scopes = $input['scopes'] ?? null;
        $this->redirectUris = $input['redirectUris'] ?? null;
        $this->grantTypes = $input['grantTypes'] ?? null;
        $this->issuerUrl = $input['issuerUrl'] ?? null;
        $this->entitledApplicationArn = $input['entitledApplicationArn'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   clientName?: string,
     *   clientType?: string,
     *   scopes?: string[]|null,
     *   redirectUris?: string[]|null,
     *   grantTypes?: string[]|null,
     *   issuerUrl?: string|null,
     *   entitledApplicationArn?: string|null,
     *   '@region'?: string|null,
     * }|RegisterClientRequest $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getClientName(): ?string
    {
        return $this->clientName;
    }

    public function getClientType(): ?string
    {
        return $this->clientType;
    }

    public function getEntitledApplicationArn(): ?string
    {
        return $this->entitledApplicationArn;
    }

    /**
     * @return string[]
     */
    public function getGrantTypes(): array
    {
        return $this->grantTypes ?? [];
    }

    public function getIssuerUrl(): ?string
    {
        return $this->issuerUrl;
    }

    /**
     * @return string[]
     */
    public function getRedirectUris(): array
    {
        return $this->redirectUris ?? [];
    }

    /**
     * @return string[]
     */
    public function getScopes(): array
    {
        return $this->scopes ?? [];
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/client/register';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setClientName(?string $value): self
    {
        $this->clientName = $value;

        return $this;
    }

    public function setClientType(?string $value): self
    {
        $this->clientType = $value;

        return $this;
    }

    public function setEntitledApplicationArn(?string $value): self
    {
        $this->entitledApplicationArn = $value;

        return $this;
    }

    /**
     * @param string[] $value
     */
    public function setGrantTypes(array $value): self
    {
        $this->grantTypes = $value;

        return $this;
    }

    public function setIssuerUrl(?string $value): self
    {
        $this->issuerUrl = $value;

        return $this;
    }

    /**
     * @param string[] $value
     */
    public function setRedirectUris(array $value): self
    {
        $this->redirectUris = $value;

        return $this;
    }

    /**
     * @param string[] $value
     */
    public function setScopes(array $value): self
    {
        $this->scopes = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->clientName) {
            throw new InvalidArgument(\sprintf('Missing parameter "clientName" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['clientName'] = $v;
        if (null === $v = $this->clientType) {
            throw new InvalidArgument(\sprintf('Missing parameter "clientType" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['clientType'] = $v;
        if (null !== $v = $this->scopes) {
            $index = -1;
            $payload['scopes'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['scopes'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->redirectUris) {
            $index = -1;
            $payload['redirectUris'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['redirectUris'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->grantTypes) {
            $index = -1;
            $payload['grantTypes'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['grantTypes'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->issuerUrl) {
            $payload['issuerUrl'] = $v;
        }
        if (null !== $v = $this->entitledApplicationArn) {
            $payload['entitledApplicationArn'] = $v;
        }

        return $payload;
    }
}
