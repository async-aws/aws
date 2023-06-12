<?php

namespace AsyncAws\Core\Sts\ValueObject;

/**
 * Amazon Web Services credentials for API authentication.
 */
final class Credentials
{
    /**
     * The access key ID that identifies the temporary security credentials.
     */
    private $accessKeyId;

    /**
     * The secret access key that can be used to sign requests.
     */
    private $secretAccessKey;

    /**
     * The token that users must pass to the service API to use the temporary credentials.
     */
    private $sessionToken;

    /**
     * The date on which the current credentials expire.
     */
    private $expiration;

    /**
     * @param array{
     *   AccessKeyId: string,
     *   SecretAccessKey: string,
     *   SessionToken: string,
     *   Expiration: \DateTimeImmutable,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->accessKeyId = $input['AccessKeyId'] ?? null;
        $this->secretAccessKey = $input['SecretAccessKey'] ?? null;
        $this->sessionToken = $input['SessionToken'] ?? null;
        $this->expiration = $input['Expiration'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAccessKeyId(): string
    {
        return $this->accessKeyId;
    }

    public function getExpiration(): \DateTimeImmutable
    {
        return $this->expiration;
    }

    public function getSecretAccessKey(): string
    {
        return $this->secretAccessKey;
    }

    public function getSessionToken(): string
    {
        return $this->sessionToken;
    }
}
