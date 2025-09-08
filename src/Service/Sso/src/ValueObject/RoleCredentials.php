<?php

namespace AsyncAws\Sso\ValueObject;

/**
 * Provides information about the role credentials that are assigned to the user.
 */
final class RoleCredentials
{
    /**
     * The identifier used for the temporary security credentials. For more information, see Using Temporary Security
     * Credentials to Request Access to AWS Resources [^1] in the *AWS IAM User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/IAM/latest/UserGuide/id_credentials_temp_use-resources.html
     *
     * @var string|null
     */
    private $accessKeyId;

    /**
     * The key that is used to sign the request. For more information, see Using Temporary Security Credentials to Request
     * Access to AWS Resources [^1] in the *AWS IAM User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/IAM/latest/UserGuide/id_credentials_temp_use-resources.html
     *
     * @var string|null
     */
    private $secretAccessKey;

    /**
     * The token used for temporary credentials. For more information, see Using Temporary Security Credentials to Request
     * Access to AWS Resources [^1] in the *AWS IAM User Guide*.
     *
     * [^1]: https://docs.aws.amazon.com/IAM/latest/UserGuide/id_credentials_temp_use-resources.html
     *
     * @var string|null
     */
    private $sessionToken;

    /**
     * The date on which temporary security credentials expire.
     *
     * @var int|null
     */
    private $expiration;

    /**
     * @param array{
     *   accessKeyId?: string|null,
     *   secretAccessKey?: string|null,
     *   sessionToken?: string|null,
     *   expiration?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->accessKeyId = $input['accessKeyId'] ?? null;
        $this->secretAccessKey = $input['secretAccessKey'] ?? null;
        $this->sessionToken = $input['sessionToken'] ?? null;
        $this->expiration = $input['expiration'] ?? null;
    }

    /**
     * @param array{
     *   accessKeyId?: string|null,
     *   secretAccessKey?: string|null,
     *   sessionToken?: string|null,
     *   expiration?: int|null,
     * }|RoleCredentials $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAccessKeyId(): ?string
    {
        return $this->accessKeyId;
    }

    public function getExpiration(): ?int
    {
        return $this->expiration;
    }

    public function getSecretAccessKey(): ?string
    {
        return $this->secretAccessKey;
    }

    public function getSessionToken(): ?string
    {
        return $this->sessionToken;
    }
}
