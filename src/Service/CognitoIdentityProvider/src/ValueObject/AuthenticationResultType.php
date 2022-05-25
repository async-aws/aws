<?php

namespace AsyncAws\CognitoIdentityProvider\ValueObject;

/**
 * The result of the authentication response. This is only returned if the caller doesn't need to pass another
 * challenge. If the caller does need to pass another challenge before it gets tokens, `ChallengeName`,
 * `ChallengeParameters`, and `Session` are returned.
 */
final class AuthenticationResultType
{
    /**
     * A valid access token that Amazon Cognito issued to the user who you want to authenticate.
     */
    private $accessToken;

    /**
     * The expiration period of the authentication result in seconds.
     */
    private $expiresIn;

    /**
     * The token type.
     */
    private $tokenType;

    /**
     * The refresh token.
     */
    private $refreshToken;

    /**
     * The ID token.
     */
    private $idToken;

    /**
     * The new device metadata from an authentication result.
     */
    private $newDeviceMetadata;

    /**
     * @param array{
     *   AccessToken?: null|string,
     *   ExpiresIn?: null|int,
     *   TokenType?: null|string,
     *   RefreshToken?: null|string,
     *   IdToken?: null|string,
     *   NewDeviceMetadata?: null|NewDeviceMetadataType|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->accessToken = $input['AccessToken'] ?? null;
        $this->expiresIn = $input['ExpiresIn'] ?? null;
        $this->tokenType = $input['TokenType'] ?? null;
        $this->refreshToken = $input['RefreshToken'] ?? null;
        $this->idToken = $input['IdToken'] ?? null;
        $this->newDeviceMetadata = isset($input['NewDeviceMetadata']) ? NewDeviceMetadataType::create($input['NewDeviceMetadata']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function getExpiresIn(): ?int
    {
        return $this->expiresIn;
    }

    public function getIdToken(): ?string
    {
        return $this->idToken;
    }

    public function getNewDeviceMetadata(): ?NewDeviceMetadataType
    {
        return $this->newDeviceMetadata;
    }

    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }

    public function getTokenType(): ?string
    {
        return $this->tokenType;
    }
}
