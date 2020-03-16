<?php

namespace AsyncAws\Core\Sts\ValueObject;

use AsyncAws\Core\Exception\InvalidArgument;

class Credentials
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
     *   Expiration: \DateTimeInterface,
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

    public function getExpiration(): \DateTimeInterface
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

    public function validate(): void
    {
        if (null === $this->AccessKeyId) {
            throw new InvalidArgument(sprintf('Missing parameter "AccessKeyId" when validating the "%s". The value cannot be null.', __CLASS__));
        }

        if (null === $this->SecretAccessKey) {
            throw new InvalidArgument(sprintf('Missing parameter "SecretAccessKey" when validating the "%s". The value cannot be null.', __CLASS__));
        }

        if (null === $this->SessionToken) {
            throw new InvalidArgument(sprintf('Missing parameter "SessionToken" when validating the "%s". The value cannot be null.', __CLASS__));
        }

        if (null === $this->Expiration) {
            throw new InvalidArgument(sprintf('Missing parameter "Expiration" when validating the "%s". The value cannot be null.', __CLASS__));
        }
    }
}
