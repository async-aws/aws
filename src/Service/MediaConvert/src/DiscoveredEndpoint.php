<?php

namespace AsyncAws\MediaConvert;

use AsyncAws\Core\EndpointDiscovery\EndpointInterface;

/**
 * @internal
 */
final class DiscoveredEndpoint implements EndpointInterface
{
    /**
     * @var string
     */
    private $address;

    public function __construct(string $address)
    {
        $this->address = $address;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getCachePeriodInMinutes(): int
    {
        // 365 days of caching, as it actually does not expire.
        // Not using PHP_INT_MAX avoids integer overflowing when computing the expiration timestamp
        return 525600;
    }
}
