<?php

namespace AsyncAws\Symfony\Bundle\Secrets;

use Symfony\Component\DependencyInjection\EnvVarLoaderInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class CachedEnvVarLoader implements EnvVarLoaderInterface
{
    private $decorated;

    private $cache;

    private $ttl;

    public function __construct(EnvVarLoaderInterface $decorated, CacheInterface $cache, int $ttl)
    {
        $this->decorated = $decorated;
        $this->cache = $cache;
        $this->ttl = $ttl;
    }

    public function loadEnvVars(): array
    {
        return $this->cache->get('AsyncAws.Secrets', function (ItemInterface $item) {
            $item->expiresAfter($this->ttl);

            return $this->decorated->loadEnvVars();
        });
    }
}
