<?php

declare(strict_types=1);

namespace AsyncAws\Core\Credentials;

use AsyncAws\Core\Configuration;

/**
 * Immutable store for Credentials parameters.
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
final class Credentials implements CredentialProvider
{
    private const EXPIRATION_DRIFT = 30;

    private $accessKeyId;

    private $secretKey;

    private $sessionToken;

    private $expireDate;

    private $cacheable;

    public function __construct(
        string $accessKeyId,
        string $secretKey,
        ?string $sessionToken = null,
        ?\DateTimeImmutable $expireDate = null,
        bool $cacheable = false
    ) {
        $this->accessKeyId = $accessKeyId;
        $this->secretKey = $secretKey;
        $this->sessionToken = $sessionToken;
        $this->expireDate = $expireDate;
        $this->cacheable = $cacheable;
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

    public function getExpireDate(): ?\DateTimeImmutable
    {
        return $this->expireDate;
    }

    public function isExpired(): bool
    {
        return null !== $this->expireDate && new \DateTimeImmutable() >= $this->expireDate;
    }

    public function getCredentials(Configuration $configuration): ?Credentials
    {
        return $this->isExpired() ? null : $this;
    }

    public static function adjustExpireDate(\DateTimeImmutable $expireDate, ?\DateTimeImmutable $reference = null): \DateTimeImmutable
    {
        if (null === $expireDate) {
            return $expireDate;
        }

        if (null !== $reference) {
            $expireDate = (new \DateTimeImmutable())->add($reference->diff($expireDate));
        }

        return $expireDate->sub(new \DateInterval(sprintf('PT%dS', self::EXPIRATION_DRIFT)));
    }

    public function isCacheable(): bool
    {
        return $this->cacheable;
    }
}
