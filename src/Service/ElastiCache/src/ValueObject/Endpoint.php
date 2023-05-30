<?php

namespace AsyncAws\ElastiCache\ValueObject;

/**
 * Represents the information required for client programs to connect to a cache node.
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
