<?php

namespace AsyncAws\CloudWatch\ValueObject;

/**
 * The metric to return, including the metric name, namespace, and dimensions.
 */
final class Metric
{
    /**
     * The namespace of the metric.
     */
    private $namespace;

    /**
     * The name of the metric. This is a required field.
     */
    private $metricName;

    /**
     * The dimensions for the metric.
     */
    private $dimensions;

    /**
     * @param array{
     *   Namespace?: null|string,
     *   MetricName?: null|string,
     *   Dimensions?: null|Dimension[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->namespace = $input['Namespace'] ?? null;
        $this->metricName = $input['MetricName'] ?? null;
        $this->dimensions = isset($input['Dimensions']) ? array_map([Dimension::class, 'create'], $input['Dimensions']) : null;
    }

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
            $index = 0;
            foreach ($v as $mapValue) {
                ++$index;
                foreach ($mapValue->requestBody() as $bodyKey => $bodyValue) {
                    $payload["Dimensions.member.$index.$bodyKey"] = $bodyValue;
                }
            }
        }

        return $payload;
    }
}
