<?php

namespace AsyncAws\CognitoIdentityProvider\ValueObject;

/**
 * The result of the authentication response. This is only returned if the caller does not need to pass another
 * challenge. If the caller does need to pass another challenge before it gets tokens, `ChallengeName`,
 * `ChallengeParameters`, and `Session` are returned.
 */
final class AuthenticationResultType
{
    /**
     * The access token.
     */
    private $AccessToken;

    /**
     * The expiration period of the authentication result in seconds.
     */
    private $ExpiresIn;

    /**
     * The token type.
     */
    private $TokenType;

    /**
     * The refresh token.
     */
    private $RefreshToken;

    /**
     * The ID token.
     */
    private $IdToken;

    /**
     * The new device metadata from an authentication result.
     */
    private $NewDeviceMetadata;

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
        $this->AccessToken = $input['AccessToken'] ?? null;
        $this->ExpiresIn = $input['ExpiresIn'] ?? null;
        $this->TokenType = $input['TokenType'] ?? null;
        $this->RefreshToken = $input['RefreshToken'] ?? null;
        $this->IdToken = $input['IdToken'] ?? null;
        $this->NewDeviceMetadata = isset($input['NewDeviceMetadata']) ? NewDeviceMetadataType::create($input['NewDeviceMetadata']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAccessToken(): ?string
    {
        return $this->AccessToken;
    }

    public function getExpiresIn(): ?int
    {
        return $this->ExpiresIn;
    }

    public function getIdToken(): ?string
    {
        return $this->IdToken;
    }

    public function getNewDeviceMetadata(): ?NewDeviceMetadataType
    {
        return $this->NewDeviceMetadata;
    }

    public function getRefreshToken(): ?string
    {
        return $this->RefreshToken;
    }

    public function getTokenType(): ?string
    {
        return $this->TokenType;
    }
}
