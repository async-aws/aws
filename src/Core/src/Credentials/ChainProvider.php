<?php

declare(strict_types=1);

namespace AsyncAws\Core\Credentials;

use AsyncAws\Core\Configuration;
use Symfony\Contracts\Service\ResetInterface;

/**
 * Chains several CredentialProvider together.
 *
 * Credentials are fetched from the first CredentialProvider that does not returns null.
 * The CredentialProvider will be memoized and will be directly called the next times.
 *
 * @author Jérémy Derussé <jeremy@derusse.com>
 */
class ChainProvider implements CredentialProvider, ResetInterface
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
}
