<?php

namespace AsyncAws\SsoOidc\Result;

use AsyncAws\Core\Response;
use AsyncAws\Core\Result;

class CreateTokenResponse extends Result
{
    /**
     * A bearer token to access Amazon Web Services accounts and applications assigned to a user.
     *
     * @var string|null
     */
    private $accessToken;

    /**
     * Used to notify the client that the returned token is an access token. The supported token type is `Bearer`.
     *
     * @var string|null
     */
    private $tokenType;

    /**
     * Indicates the time in seconds when an access token will expire.
     *
     * @var int|null
     */
    private $expiresIn;

    /**
     * A token that, if present, can be used to refresh a previously issued access token that might have expired.
     *
     * For more information about the features and limitations of the current IAM Identity Center OIDC implementation, see
     * *Considerations for Using this Guide* in the IAM Identity Center OIDC API Reference [^1].
     *
     * [^1]: https://docs.aws.amazon.com/singlesignon/latest/OIDCAPIReference/Welcome.html
     *
     * @var string|null
     */
    private $refreshToken;

    /**
     * The `idToken` is not implemented or supported. For more information about the features and limitations of the current
     * IAM Identity Center OIDC implementation, see *Considerations for Using this Guide* in the IAM Identity Center OIDC
     * API Reference [^1].
     *
     * A JSON Web Token (JWT) that identifies who is associated with the issued access token.
     *
     * [^1]: https://docs.aws.amazon.com/singlesignon/latest/OIDCAPIReference/Welcome.html
     *
     * @var string|null
     */
    private $idToken;

    public function getAccessToken(): ?string
    {
        $this->initialize();

        return $this->accessToken;
    }

    public function getExpiresIn(): ?int
    {
        $this->initialize();

        return $this->expiresIn;
    }

    public function getIdToken(): ?string
    {
        $this->initialize();

        return $this->idToken;
    }

    public function getRefreshToken(): ?string
    {
        $this->initialize();

        return $this->refreshToken;
    }

    public function getTokenType(): ?string
    {
        $this->initialize();

        return $this->tokenType;
    }

    protected function populateResult(Response $response): void
    {
        $data = $response->toArray();

        $this->accessToken = isset($data['accessToken']) ? (string) $data['accessToken'] : null;
        $this->tokenType = isset($data['tokenType']) ? (string) $data['tokenType'] : null;
        $this->expiresIn = isset($data['expiresIn']) ? (int) $data['expiresIn'] : null;
        $this->refreshToken = isset($data['refreshToken']) ? (string) $data['refreshToken'] : null;
        $this->idToken = isset($data['idToken']) ? (string) $data['idToken'] : null;
    }
}
