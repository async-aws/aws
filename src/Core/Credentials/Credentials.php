<?php

declare(strict_types=1);

namespace AsyncAws\Core\Credentials;

use AsyncAws\Core\Configuration;

/**
 * Immutable store for Credentials parameters.
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
class Credentials implements CredentialProvider
{
    private $accessKeyId;
    private $secretKey;
    private $sessionToken;
    private $expireDate;

    public function __construct(
        string $accessKeyId,
        string $secretKey,
        ?string $sessionToken = null,
        ?\DateTimeInterface $expireDate = null
    ) {
        $this->accessKeyId = $accessKeyId;
        $this->secretKey = $secretKey;
        $this->sessionToken = $sessionToken;
        $this->expireDate = $expireDate;
    }

    public function getAccessKeyId(): string
    {
        return $this->accessKeyId;
    }

    public function getSecretKey(): string
    {
        return $this->secretKey;
    }

    public function getSessionToken(): ?string
    {
        return $this->sessionToken;
    }

    public function getExpireDate(): ?\DateTimeInterface
    {
        return $this->expireDate;
    }

    public function isExpired(): bool
    {
        return null !== $this->expireDate && new \DateTime() >= $this->expireDate;
    }

    public function getCredentials(Configuration $configuration): ?Credentials
    {
        return $this->isExpired() ? null : $this;
    }
}
