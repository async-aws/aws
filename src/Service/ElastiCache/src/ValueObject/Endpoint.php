<?php

namespace AsyncAws\ElastiCache\ValueObject;

/**
 * Represents a Memcached cluster endpoint which can be used by an application to connect to any node in the cluster.
 * The configuration endpoint will always have `.cfg` in it.
 * Example: `mem-3.9dvc4r.cfg.usw2.cache.amazonaws.com:11211`.
 */
final class Endpoint
{
    /**
     * The DNS hostname of the cache node.
     */
    private $address;

    /**
     * The port number that the cache engine is listening on.
     */
    private $port;

    /**
     * @param array{
     *   Address?: null|string,
     *   Port?: null|int,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->address = $input['Address'] ?? null;
        $this->port = $input['Port'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function getPort(): ?int
    {
        return $this->port;
    }
}
