<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\EndpointDiscovery\EndpointInterface;

/**
 * An endpoint information details.
 */
final class Endpoint implements EndpointInterface
{
    /**
     * IP address of the endpoint.
     */
    private $address;

    /**
     * Endpoint cache time to live (TTL) value.
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
