<?php

namespace AsyncAws\ElastiCache\ValueObject;

/**
 * Represents the information required for client programs to connect to a cache node. This value is read-only.
 */
final class Endpoint
{
    /**
     * The DNS hostname of the cache node.
     *
     * @var string|null
     */
    private $address;

    /**
     * The port number that the cache engine is listening on.
     *
     * @var int|null
     */
    private $port;

    /**
     * @param array{
     *   Address?: string|null,
     *   Port?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->address = $input['Address'] ?? null;
        $this->port = $input['Port'] ?? null;
    }

    /**
     * @param array{
     *   Address?: string|null,
     *   Port?: int|null,
     * }|Endpoint $input
     */
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
