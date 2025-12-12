<?php

namespace AsyncAws\CloudWatch\ValueObject;

/**
 * A set of metrics that are associated with an entity, such as a specific service or resource. Contains the entity and
 * the list of metric data associated with it.
 */
final class EntityMetricData
{
    /**
     * The entity associated with the metrics.
     *
     * @var Entity|null
     */
    private $entity;

    /**
     * The metric data.
     *
     * @var MetricDatum[]|null
     */
    private $metricData;

    /**
     * @param array{
     *   Entity?: Entity|array|null,
     *   MetricData?: array<MetricDatum|array>|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->entity = isset($input['Entity']) ? Entity::create($input['Entity']) : null;
        $this->metricData = isset($input['MetricData']) ? array_map([MetricDatum::class, 'create'], $input['MetricData']) : null;
    }

    /**
     * @param array{
     *   Entity?: Entity|array|null,
     *   MetricData?: array<MetricDatum|array>|null,
     * }|EntityMetricData $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getEntity(): ?Entity
    {
        return $this->entity;
    }

    /**
     * @return MetricDatum[]
     */
    public function getMetricData(): array
    {
        return $this->metricData ?? [];
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->entity) {
            $payload['Entity'] = $v->requestBody();
        }
        if (null !== $v = $this->metricData) {
            $index = -1;
            $payload['MetricData'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['MetricData'][$index] = $listValue->requestBody();
            }
        }

        return $payload;
    }
}
