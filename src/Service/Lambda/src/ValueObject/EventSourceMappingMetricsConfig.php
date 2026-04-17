<?php

namespace AsyncAws\Lambda\ValueObject;

use AsyncAws\Lambda\Enum\EventSourceMappingMetric;

/**
 * The metrics configuration for your event source. Use this configuration object to define which metrics you want your
 * event source mapping to produce.
 */
final class EventSourceMappingMetricsConfig
{
    /**
     * The metrics you want your event source mapping to produce, including `EventCount`, `ErrorCount`, `KafkaMetrics`.
     *
     * - `EventCount` to receive metrics related to the number of events processed by your event source mapping.
     * - `ErrorCount` (Amazon MSK and self-managed Apache Kafka) to receive metrics related to the number of errors in your
     *   event source mapping processing.
     * - `KafkaMetrics` (Amazon MSK and self-managed Apache Kafka) to receive metrics related to the Kafka consumers from
     *   your event source mapping.
     *
     * For more information about these metrics, see Event source mapping metrics [^1].
     *
     * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/monitoring-metrics-types.html#event-source-mapping-metrics
     *
     * @var list<EventSourceMappingMetric::*>|null
     */
    private $metrics;

    /**
     * @param array{
     *   Metrics?: array<EventSourceMappingMetric::*>|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->metrics = $input['Metrics'] ?? null;
    }

    /**
     * @param array{
     *   Metrics?: array<EventSourceMappingMetric::*>|null,
     * }|EventSourceMappingMetricsConfig $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return list<EventSourceMappingMetric::*>
     */
    public function getMetrics(): array
    {
        return $this->metrics ?? [];
    }
}
