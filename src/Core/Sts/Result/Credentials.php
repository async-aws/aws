<?php

namespace AsyncAws\Core\Sts\Result;

class Credentials
{
    private $AccessKeyId;

    private $SecretAccessKey;

    private $SessionToken;

    private $Expiration;

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @param array{
     *   AccessKeyId: string,
     *   SecretAccessKey: string,
     *   SessionToken: string,
     *   Expiration: \DateTimeImmutable|string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->AccessKeyId = $input['AccessKeyId'] ?? null;
        $this->SecretAccessKey = $input['SecretAccessKey'] ?? null;
        $this->SessionToken = $input['SessionToken'] ?? null;
        $this->Expiration = $input['Expiration'] ?? null;
    }

    public function getAccessKeyId(): string
    {
        return $this->AccessKeyId;
    }

    public function getSecretAccessKey(): string
    {
        return $this->SecretAccessKey;
    }

    public function getSessionToken(): string
    {
        return $this->SessionToken;
    }

    public function getExpiration(): \DateTimeImmutable
    {
        return $this->Expiration;
    }
}
