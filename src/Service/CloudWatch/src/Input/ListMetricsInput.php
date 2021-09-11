<?php

namespace AsyncAws\CloudWatch\Input;

use AsyncAws\CloudWatch\Enum\RecentlyActive;
use AsyncAws\CloudWatch\ValueObject\DimensionFilter;
use AsyncAws\Core\Exception\InvalidArgument;
use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

final class ListMetricsInput extends Input
{
    /**
     * The metric namespace to filter against. Only the namespace that matches exactly will be returned.
     *
     * @var string|null
     */
    private $namespace;

    /**
     * The name of the metric to filter against. Only the metrics with names that match exactly will be returned.
     *
     * @var string|null
     */
    private $metricName;

    /**
     * The dimensions to filter against. Only the dimensions that match exactly will be returned.
     *
     * @var DimensionFilter[]|null
     */
    private $dimensions;

    /**
     * The token returned by a previous call to indicate that there is more data available.
     *
     * @var string|null
     */
    private $nextToken;

    /**
     * To filter the results to show only metrics that have had data points published in the past three hours, specify this
     * parameter with a value of `PT3H`. This is the only valid value for this parameter.
     *
     * @var RecentlyActive::*|null
     */
    private $recentlyActive;

    /**
     * @param array{
     *   Namespace?: string,
     *   MetricName?: string,
     *   Dimensions?: DimensionFilter[],
     *   NextToken?: string,
     *   RecentlyActive?: RecentlyActive::*,
     *   @region?: string,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->namespace = $input['Namespace'] ?? null;
        $this->metricName = $input['MetricName'] ?? null;
        $this->dimensions = isset($input['Dimensions']) ? array_map([DimensionFilter::class, 'create'], $input['Dimensions']) : null;
        $this->nextToken = $input['NextToken'] ?? null;
        $this->recentlyActive = $input['RecentlyActive'] ?? null;
        parent::__construct($input);
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return DimensionFilter[]
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

    public function getNextToken(): ?string
    {
        return $this->nextToken;
    }

    /**
     * @return RecentlyActive::*|null
     */
    public function getRecentlyActive(): ?string
    {
        return $this->recentlyActive;
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
        $body = http_build_query(['Action' => 'ListMetrics', 'Version' => '2010-08-01'] + $this->requestBody(), '', '&', \PHP_QUERY_RFC1738);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    /**
     * @param DimensionFilter[] $value
     */
    public function setDimensions(array $value): self
    {
        $this->dimensions = $value;

        return $this;
    }

    public function setMetricName(?string $value): self
    {
        $this->metricName = $value;

        return $this;
    }

    public function setNamespace(?string $value): self
    {
        $this->namespace = $value;

        return $this;
    }

    public function setNextToken(?string $value): self
    {
        $this->nextToken = $value;

        return $this;
    }

    /**
     * @param RecentlyActive::*|null $value
     */
    public function setRecentlyActive(?string $value): self
    {
        $this->recentlyActive = $value;

        return $this;
    }

    private function requestBody(): array
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
        if (null !== $v = $this->nextToken) {
            $payload['NextToken'] = $v;
        }
        if (null !== $v = $this->recentlyActive) {
            if (!RecentlyActive::exists($v)) {
                throw new InvalidArgument(sprintf('Invalid parameter "RecentlyActive" for "%s". The value "%s" is not a valid "RecentlyActive".', __CLASS__, $v));
            }
            $payload['RecentlyActive'] = $v;
        }

        return $payload;
    }
}
