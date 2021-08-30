<?php

namespace AsyncAws\CloudWatch\Input;

use AsyncAws\CloudWatch\ValueObject\MetricDatum;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class PutMetricDataInput extends Input
{
    /**
     * The namespace for the metric data.
     *
     * @required
     *
     * @var string|null
     */
    private $namespace;

    /**
     * The data for the metric. The array can include no more than 20 metrics per call.
     *
     * @required
     *
     * @var MetricDatum[]|null
     */
    private $metricData;

    /**
     * @param array{
     *   Namespace?: string,
     *   MetricData?: MetricDatum[],
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->namespace = $input['Namespace'] ?? null;
        $this->metricData = isset($input['MetricData']) ? array_map([MetricDatum::class, 'create'], $input['MetricData']) : null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return MetricDatum[]
     */
    public function getMetricData(): array
    {
        return $this->metricData ?? [];
    }

    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    /**
     * @internal
     */
    public function request(): Request
    {
        // Prepare headers
        $headers = ['content-type' => 'application/x-www-form-urlencoded'];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $body = http_build_query(['Action' => 'PutMetricData', 'Version' => '2010-08-01'] + $this->requestBody(), '', '&', \PHP_QUERY_RFC1738);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    /**
     * @param MetricDatum[] $value
     */
    public function setMetricData(array $value): self
    {
        $this->metricData = $value;

        return $this;
    }

    public function setNamespace(?string $value): self
    {
        $this->namespace = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null === $v = $this->namespace) {
            throw new InvalidArgument(sprintf('Missing parameter "Namespace" for "%s". The value cannot be null.', __CLASS__));
        }
        $payload['Namespace'] = $v;
        if (null === $v = $this->metricData) {
            throw new InvalidArgument(sprintf('Missing parameter "MetricData" for "%s". The value cannot be null.', __CLASS__));
        }

        $index = 0;
        foreach ($v as $mapValue) {
            ++$index;
            foreach ($mapValue->requestBody() as $bodyKey => $bodyValue) {
                $payload["MetricData.member.$index.$bodyKey"] = $bodyValue;
            }
        }

        return $payload;
    }
}
