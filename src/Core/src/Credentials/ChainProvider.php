<?php

declare(strict_types=1);

namespace AsyncAws\Core\Credentials;

use AsyncAws\Core\Configuration;
use AsyncAws\Core\Exception\InvalidArgument;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\Service\ResetInterface;

/**
 * Chains several CredentialProvider together.
 *
 * Credentials are fetched from the first CredentialProvider that does not returns null.
 * The CredentialProvider will be memoized and will be directly called the next times.
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
final class ChainProvider implements CredentialProvider, ResetInterface
{
    private $providers;

    /**
     * @var (CredentialProvider|null)[]
     */
    private $lastSuccessfulProvider = [];

    /**
     * @param CredentialProvider[] $providers
     */
    public function __construct(iterable $providers)
    {
        $this->providers = $providers;
    }

    public function getCredentials(Configuration $configuration): ?Credentials
    {
        $key = \spl_object_hash($configuration);
        if (\array_key_exists($key, $this->lastSuccessfulProvider)) {
            if (null === $provider = $this->lastSuccessfulProvider[$key]) {
                return null;
            }

            return $provider->getCredentials($configuration);
        }

        foreach ($this->providers as $provider) {
            if (null !== $credentials = $provider->getCredentials($configuration)) {
                $this->lastSuccessfulProvider[$key] = $provider;

                return $credentials;
            }
        }

        $this->lastSuccessfulProvider[$key] = null;

        return null;
    }

    public function reset()
    {
        $this->lastSuccessfulProvider = [];
    }

    public static function createDefaultChain(?HttpClientInterface $httpClient = null, ?LoggerInterface $logger = null, $cache = null): CredentialProvider
    {
        $httpClient = $httpClient ?? HttpClient::create();
        $logger = $logger ?? new NullLogger();
        if (null === $cache) {
            if (\interface_exists(CacheInterface::class)) {
                $cache = new FilesystemAdapter('async-aws');
            }
        } elseif (!$cache instanceof CacheInterface) {
            throw new InvalidArgument(sprintf('Expected cache to be a "%s". Got "%s".', CacheInterface::class, \get_class($cache)));
        }

        return new CacheProvider(new ChainProvider([
            new ConfigurationProvider(),
            new WebIdentityProvider($logger),
            new IniFileProvider($logger),
            null === $cache ? new ContainerProvider($httpClient, $logger) : new PersistingCacheProvider(new ContainerProvider($httpClient, $logger), $cache),
            null === $cache ? new InstanceProvider($httpClient, $logger) : new PersistingCacheProvider(new InstanceProvider($httpClient, $logger), $cache),
        ]));
    }
}
