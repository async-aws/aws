<?php

namespace AsyncAws\Lambda\ValueObject;

use AsyncAws\Lambda\Enum\EndPointType;

/**
 * The self-managed Apache Kafka cluster for your event source.
 */
final class SelfManagedEventSource
{
    /**
     * The list of bootstrap servers for your Kafka brokers in the following format: `"KAFKA_BOOTSTRAP_SERVERS":
     * ["abc.xyz.com:xxxx","abc2.xyz.com:xxxx"]`.
     *
     * @var array<EndPointType::*, string[]>|null
     */
    private $endpoints;

    /**
     * @param array{
     *   Endpoints?: array<EndPointType::*, array>|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->endpoints = $input['Endpoints'] ?? null;
    }

    /**
     * @param array{
     *   Endpoints?: array<EndPointType::*, array>|null,
     * }|SelfManagedEventSource $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return array<EndPointType::*, string[]>
     */
    public function getEndpoints(): array
    {
        return $this->endpoints ?? [];
    }
}
