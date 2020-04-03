<?php

namespace AsyncAws\CloudWatch\ValueObject;

class Metric
{
    /**
     * The namespace of the metric.
     */
    private $Namespace;

    /**
     * The name of the metric. This is a required field.
     */
    private $MetricName;

    /**
     * The dimensions for the metric.
     */
    private $Dimensions;

    /**
     * @param array{
     *   Namespace?: null|string,
     *   MetricName?: null|string,
     *   Dimensions?: null|\AsyncAws\CloudWatch\ValueObject\Dimension[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->Namespace = $input['Namespace'] ?? null;
        $this->MetricName = $input['MetricName'] ?? null;
        $this->Dimensions = array_map([Dimension::class, 'create'], $input['Dimensions'] ?? []);
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
        return $this->Dimensions;
    }

    public function getMetricName(): ?string
    {
        return $this->MetricName;
    }

    public function getNamespace(): ?string
    {
        return $this->Namespace;
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->Namespace) {
            $payload['Namespace'] = $v;
        }
        if (null !== $v = $this->MetricName) {
            $payload['MetricName'] = $v;
        }

        $index = 0;
        foreach ($this->Dimensions as $mapValue) {
            ++$index;
            foreach ($mapValue->requestBody() as $bodyKey => $bodyValue) {
                $payload["Dimensions.member.$index.$bodyKey"] = $bodyValue;
            }
        }

        return $payload;
    }
}
