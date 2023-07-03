<?php

namespace AsyncAws\TimestreamWrite\ValueObject;

use AsyncAws\Core\EndpointDiscovery\EndpointInterface;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Represents an available endpoint against which to make API calls against, as well as the TTL for that endpoint.
 */
final class Endpoint implements EndpointInterface
{
    /**
     * An endpoint address.
     *
     * @var string
     */
    private $address;

    /**
     * The TTL for the endpoint, in minutes.
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
