<?php

namespace AsyncAws\ElastiCache\Input;

use AsyncAws\Core\Input;
use AsyncAws\Core\Request;
use AsyncAws\Core\Stream\StreamFactory;

/**
 * Represents the input of a `DescribeCacheClusters` operation.
 */
final class DescribeCacheClustersMessage extends Input
{
    /**
     * The user-supplied cluster identifier. If this parameter is specified, only information about that specific cluster is
     * returned. This parameter isn't case sensitive.
     *
     * @var string|null
     */
    private $cacheClusterId;

    /**
     * The maximum number of records to include in the response. If more records exist than the specified `MaxRecords`
     * value, a marker is included in the response so that the remaining results can be retrieved.
     *
     * Default: 100
     *
     * Constraints: minimum 20; maximum 100.
     *
     * @var int|null
     */
    private $maxRecords;

    /**
     * An optional marker returned from a prior request. Use this marker for pagination of results from this operation. If
     * this parameter is specified, the response includes only records beyond the marker, up to the value specified by
     * `MaxRecords`.
     *
     * @var string|null
     */
    private $marker;

    /**
     * An optional flag that can be included in the `DescribeCacheCluster` request to retrieve information about the
     * individual cache nodes.
     *
     * @var bool|null
     */
    private $showCacheNodeInfo;

    /**
     * An optional flag that can be included in the `DescribeCacheCluster` request to show only nodes (API/CLI: clusters)
     * that are not members of a replication group. In practice, this means Memcached and single node Valkey or Redis OSS
     * clusters.
     *
     * @var bool|null
     */
    private $showCacheClustersNotInReplicationGroups;

    /**
     * @param array{
     *   CacheClusterId?: string|null,
     *   MaxRecords?: int|null,
     *   Marker?: string|null,
     *   ShowCacheNodeInfo?: bool|null,
     *   ShowCacheClustersNotInReplicationGroups?: bool|null,
     *   '@region'?: string|null,
     * } $input
     */
    public function __construct(array $input = [])
    {
        $this->cacheClusterId = $input['CacheClusterId'] ?? null;
        $this->maxRecords = $input['MaxRecords'] ?? null;
        $this->marker = $input['Marker'] ?? null;
        $this->showCacheNodeInfo = $input['ShowCacheNodeInfo'] ?? null;
        $this->showCacheClustersNotInReplicationGroups = $input['ShowCacheClustersNotInReplicationGroups'] ?? null;
        parent::__construct($input);
    }

    /**
     * @param array{
     *   CacheClusterId?: string|null,
     *   MaxRecords?: int|null,
     *   Marker?: string|null,
     *   ShowCacheNodeInfo?: bool|null,
     *   ShowCacheClustersNotInReplicationGroups?: bool|null,
     *   '@region'?: string|null,
     * }|DescribeCacheClustersMessage $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCacheClusterId(): ?string
    {
        return $this->cacheClusterId;
    }

    public function getMarker(): ?string
    {
        return $this->marker;
    }

    public function getMaxRecords(): ?int
    {
        return $this->maxRecords;
    }

    public function getShowCacheClustersNotInReplicationGroups(): ?bool
    {
        return $this->showCacheClustersNotInReplicationGroups;
    }

    public function getShowCacheNodeInfo(): ?bool
    {
        return $this->showCacheNodeInfo;
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
        $body = http_build_query(['Action' => 'DescribeCacheClusters', 'Version' => '2015-02-02'] + $this->requestBody(), '', '&', \PHP_QUERY_RFC1738);

        // Return the Request
        return new Request('POST', $uriString, $query, $headers, StreamFactory::create($body));
    }

    public function setCacheClusterId(?string $value): self
    {
        $this->cacheClusterId = $value;

        return $this;
    }

    public function setMarker(?string $value): self
    {
        $this->marker = $value;

        return $this;
    }

    public function setMaxRecords(?int $value): self
    {
        $this->maxRecords = $value;

        return $this;
    }

    public function setShowCacheClustersNotInReplicationGroups(?bool $value): self
    {
        $this->showCacheClustersNotInReplicationGroups = $value;

        return $this;
    }

    public function setShowCacheNodeInfo(?bool $value): self
    {
        $this->showCacheNodeInfo = $value;

        return $this;
    }

    private function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->cacheClusterId) {
            $payload['CacheClusterId'] = $v;
        }
        if (null !== $v = $this->maxRecords) {
            $payload['MaxRecords'] = $v;
        }
        if (null !== $v = $this->marker) {
            $payload['Marker'] = $v;
        }
        if (null !== $v = $this->showCacheNodeInfo) {
            $payload['ShowCacheNodeInfo'] = $v ? 'true' : 'false';
        }
        if (null !== $v = $this->showCacheClustersNotInReplicationGroups) {
            $payload['ShowCacheClustersNotInReplicationGroups'] = $v ? 'true' : 'false';
        }

        return $payload;
    }
}
