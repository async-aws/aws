<?php

namespace AsyncAws\ElastiCache\ValueObject;

/**
 * Represents an individual cache node within a cluster. Each cache node runs its own instance of the cluster's
 * protocol-compliant caching software - either Memcached, Valkey or Redis OSS.
 *
 * The following node types are supported by ElastiCache. Generally speaking, the current generation types provide more
 * memory and computational power at lower cost when compared to their equivalent previous generation counterparts.
 *
 * - General purpose:
 *
 *   - Current generation:
 *
 *     **M7g node types**: `cache.m7g.large`, `cache.m7g.xlarge`, `cache.m7g.2xlarge`, `cache.m7g.4xlarge`,
 *     `cache.m7g.8xlarge`, `cache.m7g.12xlarge`, `cache.m7g.16xlarge`
 *
 *     > For region availability, see Supported Node Types [^1]
 *
 *     **M6g node types** (available only for Redis OSS engine version 5.0.6 onward and for Memcached engine version
 *     1.5.16 onward): `cache.m6g.large`, `cache.m6g.xlarge`, `cache.m6g.2xlarge`, `cache.m6g.4xlarge`,
 *     `cache.m6g.8xlarge`, `cache.m6g.12xlarge`, `cache.m6g.16xlarge`
 *
 *     **M5 node types:**`cache.m5.large`, `cache.m5.xlarge`, `cache.m5.2xlarge`, `cache.m5.4xlarge`,
 *     `cache.m5.12xlarge`, `cache.m5.24xlarge`
 *
 *     **M4 node types:**`cache.m4.large`, `cache.m4.xlarge`, `cache.m4.2xlarge`, `cache.m4.4xlarge`,
 *     `cache.m4.10xlarge`
 *
 *     **T4g node types** (available only for Redis OSS engine version 5.0.6 onward and Memcached engine version 1.5.16
 *     onward): `cache.t4g.micro`, `cache.t4g.small`, `cache.t4g.medium`
 *
 *     **T3 node types:**`cache.t3.micro`, `cache.t3.small`, `cache.t3.medium`
 *
 *     **T2 node types:**`cache.t2.micro`, `cache.t2.small`, `cache.t2.medium`
 *   - Previous generation: (not recommended. Existing clusters are still supported but creation of new clusters is not
 *     supported for these types.)
 *
 *     **T1 node types:**`cache.t1.micro`
 *
 *     **M1 node types:**`cache.m1.small`, `cache.m1.medium`, `cache.m1.large`, `cache.m1.xlarge`
 *
 *     **M3 node types:**`cache.m3.medium`, `cache.m3.large`, `cache.m3.xlarge`, `cache.m3.2xlarge`
 *
 * - Compute optimized:
 *
 *   - Previous generation: (not recommended. Existing clusters are still supported but creation of new clusters is not
 *     supported for these types.)
 *
 *     **C1 node types:**`cache.c1.xlarge`
 *
 * - Memory optimized:
 *
 *   - Current generation:
 *
 *     **R7g node types**: `cache.r7g.large`, `cache.r7g.xlarge`, `cache.r7g.2xlarge`, `cache.r7g.4xlarge`,
 *     `cache.r7g.8xlarge`, `cache.r7g.12xlarge`, `cache.r7g.16xlarge`
 *
 *     > For region availability, see Supported Node Types [^2]
 *
 *     **R6g node types** (available only for Redis OSS engine version 5.0.6 onward and for Memcached engine version
 *     1.5.16 onward): `cache.r6g.large`, `cache.r6g.xlarge`, `cache.r6g.2xlarge`, `cache.r6g.4xlarge`,
 *     `cache.r6g.8xlarge`, `cache.r6g.12xlarge`, `cache.r6g.16xlarge`
 *
 *     **R5 node types:**`cache.r5.large`, `cache.r5.xlarge`, `cache.r5.2xlarge`, `cache.r5.4xlarge`,
 *     `cache.r5.12xlarge`, `cache.r5.24xlarge`
 *
 *     **R4 node types:**`cache.r4.large`, `cache.r4.xlarge`, `cache.r4.2xlarge`, `cache.r4.4xlarge`,
 *     `cache.r4.8xlarge`, `cache.r4.16xlarge`
 *   - Previous generation: (not recommended. Existing clusters are still supported but creation of new clusters is not
 *     supported for these types.)
 *
 *     **M2 node types:**`cache.m2.xlarge`, `cache.m2.2xlarge`, `cache.m2.4xlarge`
 *
 *     **R3 node types:**`cache.r3.large`, `cache.r3.xlarge`, `cache.r3.2xlarge`, `cache.r3.4xlarge`, `cache.r3.8xlarge`
 *
 *
 * **Additional node type info**
 *
 * - All current generation instance types are created in Amazon VPC by default.
 * - Valkey or Redis OSS append-only files (AOF) are not supported for T1 or T2 instances.
 * - Valkey or Redis OSS Multi-AZ with automatic failover is not supported on T1 instances.
 * - The configuration variables `appendonly` and `appendfsync` are not supported on Valkey, or on Redis OSS version
 *   2.8.22 and later.
 *
 * [^1]: https://docs.aws.amazon.com/AmazonElastiCache/latest/dg/CacheNodes.SupportedTypes.html#CacheNodes.SupportedTypesByRegion
 * [^2]: https://docs.aws.amazon.com/AmazonElastiCache/latest/dg/CacheNodes.SupportedTypes.html#CacheNodes.SupportedTypesByRegion
 */
final class CacheNode
{
    /**
     * The cache node identifier. A node ID is a numeric identifier (0001, 0002, etc.). The combination of cluster ID and
     * node ID uniquely identifies every cache node used in a customer's Amazon account.
     *
     * @var string|null
     */
    private $cacheNodeId;

    /**
     * The current state of this cache node, one of the following values: `available`, `creating`, `rebooting`, or
     * `deleting`.
     *
     * @var string|null
     */
    private $cacheNodeStatus;

    /**
     * The date and time when the cache node was created.
     *
     * @var \DateTimeImmutable|null
     */
    private $cacheNodeCreateTime;

    /**
     * The hostname for connecting to this cache node.
     *
     * @var Endpoint|null
     */
    private $endpoint;

    /**
     * The status of the parameter group applied to this cache node.
     *
     * @var string|null
     */
    private $parameterGroupStatus;

    /**
     * The ID of the primary node to which this read replica node is synchronized. If this field is empty, this node is not
     * associated with a primary cluster.
     *
     * @var string|null
     */
    private $sourceCacheNodeId;

    /**
     * The Availability Zone where this node was created and now resides.
     *
     * @var string|null
     */
    private $customerAvailabilityZone;

    /**
     * The customer outpost ARN of the cache node.
     *
     * @var string|null
     */
    private $customerOutpostArn;

    /**
     * @param array{
     *   CacheNodeId?: string|null,
     *   CacheNodeStatus?: string|null,
     *   CacheNodeCreateTime?: \DateTimeImmutable|null,
     *   Endpoint?: Endpoint|array|null,
     *   ParameterGroupStatus?: string|null,
     *   SourceCacheNodeId?: string|null,
     *   CustomerAvailabilityZone?: string|null,
     *   CustomerOutpostArn?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->cacheNodeId = $input['CacheNodeId'] ?? null;
        $this->cacheNodeStatus = $input['CacheNodeStatus'] ?? null;
        $this->cacheNodeCreateTime = $input['CacheNodeCreateTime'] ?? null;
        $this->endpoint = isset($input['Endpoint']) ? Endpoint::create($input['Endpoint']) : null;
        $this->parameterGroupStatus = $input['ParameterGroupStatus'] ?? null;
        $this->sourceCacheNodeId = $input['SourceCacheNodeId'] ?? null;
        $this->customerAvailabilityZone = $input['CustomerAvailabilityZone'] ?? null;
        $this->customerOutpostArn = $input['CustomerOutpostArn'] ?? null;
    }

    /**
     * @param array{
     *   CacheNodeId?: string|null,
     *   CacheNodeStatus?: string|null,
     *   CacheNodeCreateTime?: \DateTimeImmutable|null,
     *   Endpoint?: Endpoint|array|null,
     *   ParameterGroupStatus?: string|null,
     *   SourceCacheNodeId?: string|null,
     *   CustomerAvailabilityZone?: string|null,
     *   CustomerOutpostArn?: string|null,
     * }|CacheNode $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCacheNodeCreateTime(): ?\DateTimeImmutable
    {
        return $this->cacheNodeCreateTime;
    }

    public function getCacheNodeId(): ?string
    {
        return $this->cacheNodeId;
    }

    public function getCacheNodeStatus(): ?string
    {
        return $this->cacheNodeStatus;
    }

    public function getCustomerAvailabilityZone(): ?string
    {
        return $this->customerAvailabilityZone;
    }

    public function getCustomerOutpostArn(): ?string
    {
        return $this->customerOutpostArn;
    }

    public function getEndpoint(): ?Endpoint
    {
        return $this->endpoint;
    }

    public function getParameterGroupStatus(): ?string
    {
        return $this->parameterGroupStatus;
    }

    public function getSourceCacheNodeId(): ?string
    {
        return $this->sourceCacheNodeId;
    }
}
