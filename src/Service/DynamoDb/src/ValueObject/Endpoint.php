<?php

namespace AsyncAws\DynamoDb\ValueObject;

use AsyncAws\Core\EndpointDiscovery\EndpointInterface;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * An endpoint information details.
 */
final class Endpoint implements EndpointInterface
{
    /**
     * IP address of the endpoint.
     *
     * @var string
     */
    private $address;

    /**
     * Endpoint cache time to live (TTL) value.
     *
     * @var int
     */
    private $cachePeriodInMinutes;

    /**
     * @param array{
     *   Address: string,
     *   CachePeriodInMinutes: int,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->address = $input['Address'] ?? $this->throwException(new InvalidArgument('Missing required field "Address".'));
        $this->cachePeriodInMinutes = $input['CachePeriodInMinutes'] ?? $this->throwException(new InvalidArgument('Missing required field "CachePeriodInMinutes".'));
    }

    /**
     * @param array{
     *   Address: string,
     *   CachePeriodInMinutes: int,
     * }|Endpoint $input
     */
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

    /**
     * @return never
     */
    private function throwException(\Throwable $exception)
    {
        throw $exception;
    }
}
