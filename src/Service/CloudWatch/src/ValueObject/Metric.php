<?php

namespace AsyncAws\CloudWatch\ValueObject;

/**
 * Represents a specific metric.
 */
final class Metric
{
    /**
     * The namespace of the metric.
     *
     * @var string|null
     */
    private $namespace;

    /**
     * The name of the metric. This is a required field.
     *
     * @var string|null
     */
    private $metricName;

    /**
     * The dimensions for the metric.
     *
     * @var Dimension[]|null
     */
    private $dimensions;

    /**
     * @param array{
     *   Namespace?: string|null,
     *   MetricName?: string|null,
     *   Dimensions?: array<Dimension|array>|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->namespace = $input['Namespace'] ?? null;
        $this->metricName = $input['MetricName'] ?? null;
        $this->dimensions = isset($input['Dimensions']) ? array_map([Dimension::class, 'create'], $input['Dimensions']) : null;
    }

    /**
     * @param array{
     *   Namespace?: string|null,
     *   MetricName?: string|null,
     *   Dimensions?: array<Dimension|array>|null,
     * }|Metric $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return Dimension[]
     */
    public function getDimensions(): array
    {
        return $this->dimensions ?? [];
    }

    public function getMetricName(): ?string
    {
        return $this->metricName;
    }

    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->namespace) {
            $payload['Namespace'] = $v;
        }
        if (null !== $v = $this->metricName) {
            $payload['MetricName'] = $v;
        }
        if (null !== $v = $this->dimensions) {
            $index = -1;
            $payload['Dimensions'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['Dimensions'][$index] = $listValue->requestBody();
            }
        }

        return $payload;
    }
}
