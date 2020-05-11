<?php

declare(strict_types=1);

namespace AsyncAws\Core\Credentials;

use AsyncAws\Core\Configuration;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * Cache the Credential generated by the decorated CredentialProvider with Symfony Cache.
 * Symfony Cache provides stampede protection which is preferred on applications with more than
 * 1 or 2 requests per second.
 *
 * The Credential will be reused until it expires.
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
final class SymfonyCacheProvider implements CredentialProvider
{
    private $cache;
    private $decorated;

    public function __construct(CredentialProvider $decorated, CacheInterface $cache)
    {
        $this->decorated = $decorated;
        $this->cache = $cache;
    }

    public function getCredentials(Configuration $configuration): ?Credentials
    {
        return $this->cache->get(sha1(\serialize([$configuration, \get_class($this->decorated)])), function (ItemInterface $item) use ($configuration) {
            $credential = $this->decorated->getCredentials($configuration);

            if (null !== $credential && $credential->isCacheable()) {
                $item->expiresAt($credential->getExpireDate());
            } else {
                $item->expiresAfter(0);
            }

            return $credential;
        });
    }
}
