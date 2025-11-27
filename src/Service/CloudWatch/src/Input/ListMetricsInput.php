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
     * The dimensions to filter against. Only the dimension with names that match exactly will be returned. If you specify
     * one dimension name and a metric has that dimension and also other dimensions, it will be returned.
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
     * The results that are returned are an approximation of the value you specify. There is a low probability that the
     * returned results include metrics with last published data as much as 50 minutes more than the specified time
     * interval.
     *
     * @var RecentlyActive::*|null
     */
    private $recentlyActive;

    /**
     * If you are using this operation in a monitoring account, specify `true` to include metrics from source accounts in
     * the returned data.
     *
     * The default is `false`.
     *
     * @var bool|null
     */
    private $includeLinkedAccounts;

    /**
     * When you use this operation in a monitoring account, use this field to return metrics only from one source account.
     * To do so, specify that source account ID in this field, and also specify `true` for `IncludeLinkedAccounts`.
     *
     * @var string|null
     */
    private $owningAccount;

    /**
     * @param array{
     *   Namespace?: string|null,
     *   MetricName?: string|null,
     *   Dimensions?: array<DimensionFilter|array>|null,
     *   NextToken?: string|null,
     *   RecentlyActive?: RecentlyActive::*|null,
     *   IncludeLinkedAccounts?: bool|null,
     *   OwningAccount?: string|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->namespace = $input['Namespace'] ?? null;
        $this->metricName = $input['MetricName'] ?? null;
        $this->dimensions = isset($input['Dimensions']) ? array_map([DimensionFilter::class, 'create'], $input['Dimensions']) : null;
        $this->nextToken = $input['NextToken'] ?? null;
        $this->recentlyActive = $input['RecentlyActive'] ?? null;
        $this->includeLinkedAccounts = $input['IncludeLinkedAccounts'] ?? null;
        $this->owningAccount = $input['OwningAccount'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   Namespace?: string|null,
     *   MetricName?: string|null,
     *   Dimensions?: array<DimensionFilter|array>|null,
     *   NextToken?: string|null,
     *   RecentlyActive?: RecentlyActive::*|null,
     *   IncludeLinkedAccounts?: bool|null,
     *   OwningAccount?: string|null,
     *   '@region'?: string|null,
     * }|ListMetricsInput $input
     */
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

    public function getIncludeLinkedAccounts(): ?bool
    {
        return $this->includeLinkedAccounts;
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

    public function getOwningAccount(): ?string
    {
        return $this->owningAccount;
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
        $headers = [
            'Content-Type' => 'application/x-amz-json-1.0',
            'X-Amz-Target' => 'GraniteServiceVersion20100801.ListMetrics',
            'Accept' => 'application/json',
        ];

        // Prepare query
        $query = [];

        // Prepare URI
        $uriString = '/';

        // Prepare Body
        $bodyPayload = $this->requestBody();
        $body = empty($bodyPayload) ? '{}' : json_encode($bodyPayload, 4194304);

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

    public function setIncludeLinkedAccounts(?bool $value): self
    {
        $this->includeLinkedAccounts = $value;

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

    public function setOwningAccount(?string $value): self
    {
        $this->owningAccount = $value;

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
            $index = -1;
            $payload['Dimensions'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['Dimensions'][$index] = $listValue->requestBody();
            }
        }
        if (null !== $v = $this->nextToken) {
            $payload['NextToken'] = $v;
        }
        if (null !== $v = $this->recentlyActive) {
            if (!RecentlyActive::exists($v)) {
                /** @psalm-suppress NoValue */
                throw new InvalidArgument(\sprintf('Invalid parameter "RecentlyActive" for "%s". The value "%s" is not a valid "RecentlyActive".', __CLASS__, $v));
            }
            $payload['RecentlyActive'] = $v;
        }
        if (null !== $v = $this->includeLinkedAccounts) {
            $payload['IncludeLinkedAccounts'] = (bool) $v;
        }
        if (null !== $v = $this->owningAccount) {
            $payload['OwningAccount'] = $v;
        }

        return $payload;
    }
}
