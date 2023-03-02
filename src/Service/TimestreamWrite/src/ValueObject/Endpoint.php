<?php

namespace AsyncAws\TimestreamWrite\ValueObject;

use AsyncAws\Core\EndpointDiscovery\EndpointInterface;

/**
 * Represents an available endpoint against which to make API calls against, as well as the TTL for that endpoint.
 */
final class Endpoint implements EndpointInterface
{
    /**
     * An endpoint address.
     */
    private $address;

    /**
     * The TTL for the endpoint, in minutes.
     */
    private $cachePeriodInMinutes;

    /**
     * @param array{
     *   Address: string,
     *   CachePeriodInMinutes: string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->address = $input['Address'] ?? null;
        $this->cachePeriodInMinutes = $input['CachePeriodInMinutes'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getCachePeriodInMinutes(): int
    {
        return $this->cachePeriodInMinutes;
    }
}
