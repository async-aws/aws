<?php

namespace AsyncAws\Core\Sts\ValueObject;

/**
 * The temporary security credentials, which include an access key ID, a secret access key, and a security (or session)
 * token.
 *
 * > The size of the security token that STS API operations return is not fixed. We strongly recommend that you make no
 * > assumptions about the maximum size.
 */
final class Credentials
{
    /**
     * The access key ID that identifies the temporary security credentials.
     */
    private $AccessKeyId;

    /**
     * The secret access key that can be used to sign requests.
     */
    private $SecretAccessKey;

    /**
     * The token that users must pass to the service API to use the temporary credentials.
     */
    private $SessionToken;

    /**
     * The date on which the current credentials expire.
     */
    private $Expiration;

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
        $this->AccessKeyId = $input['AccessKeyId'] ?? null;
        $this->SecretAccessKey = $input['SecretAccessKey'] ?? null;
        $this->SessionToken = $input['SessionToken'] ?? null;
        $this->Expiration = $input['Expiration'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAccessKeyId(): string
    {
        return $this->AccessKeyId;
    }

    public function getExpiration(): \DateTimeImmutable
    {
        return $this->Expiration;
    }

    public function getSecretAccessKey(): string
    {
        return $this->SecretAccessKey;
    }

    public function getSessionToken(): string
    {
        return $this->SessionToken;
    }
}
