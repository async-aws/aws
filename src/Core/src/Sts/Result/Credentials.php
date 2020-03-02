<?php

namespace AsyncAws\Core\Sts\Result;

class Credentials
{
    private $AccessKeyId;

    private $SecretAccessKey;

    private $SessionToken;

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
        $this->AccessKeyId = $input['AccessKeyId'];
        $this->SecretAccessKey = $input['SecretAccessKey'];
        $this->SessionToken = $input['SessionToken'];
        $this->Expiration = $input['Expiration'];
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * The access key ID that identifies the temporary security credentials.
     */
    public function getAccessKeyId(): string
    {
        return $this->AccessKeyId;
    }

    /**
     * The date on which the current credentials expire.
     */
    public function getExpiration(): \DateTimeInterface
    {
        return $this->Expiration;
    }

    /**
     * The secret access key that can be used to sign requests.
     */
    public function getSecretAccessKey(): string
    {
        return $this->SecretAccessKey;
    }

    /**
     * The token that users must pass to the service API to use the temporary credentials.
     */
    public function getSessionToken(): string
    {
        return $this->SessionToken;
    }
}
