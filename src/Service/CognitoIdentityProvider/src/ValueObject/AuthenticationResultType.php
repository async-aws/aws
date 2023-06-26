<?php

namespace AsyncAws\CognitoIdentityProvider\ValueObject;

/**
 * The authentication result.
 */
final class AuthenticationResultType
{
    /**
     * A valid access token that Amazon Cognito issued to the user who you want to authenticate.
     *
     * @var string|null
     */
    private $accessToken;

    /**
     * The expiration period of the authentication result in seconds.
     *
     * @var int|null
     */
    private $expiresIn;

    /**
     * The token type.
     *
     * @var string|null
     */
    private $tokenType;

    /**
     * The refresh token.
     *
     * @var string|null
     */
    private $refreshToken;

    /**
     * The ID token.
     *
     * @var string|null
     */
    private $idToken;

    /**
     * The new device metadata from an authentication result.
     *
     * @var NewDeviceMetadataType|null
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

    /**
     * @param array{
     *   AccessToken?: null|string,
     *   ExpiresIn?: null|int,
     *   TokenType?: null|string,
     *   RefreshToken?: null|string,
     *   IdToken?: null|string,
     *   NewDeviceMetadata?: null|NewDeviceMetadataType|array,
     * }|AuthenticationResultType $input
     */
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
