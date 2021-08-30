<?php

namespace AsyncAws\ElastiCache\ValueObject;

/**
 * Represents an individual cache node within a cluster. Each cache node runs its own instance of the cluster's
 * protocol-compliant caching software - either Memcached or Redis.
 * The following node types are supported by ElastiCache. Generally speaking, the current generation types provide more
 * memory and computational power at lower cost when compared to their equivalent previous generation counterparts.
 *
 * - General purpose:
 *
 *   - Current generation:
 *     **M6g node types** (available only for Redis engine version 5.0.6 onward and for Memcached engine version 1.5.16
 *     onward).
 *     `cache.m6g.large`, `cache.m6g.xlarge`, `cache.m6g.2xlarge`, `cache.m6g.4xlarge`, `cache.m6g.8xlarge`,
 *     `cache.m6g.12xlarge`, `cache.m6g.16xlarge`
 *
 *     > For region availability, see Supported Node Types
 *
 *     **M5 node types:**`cache.m5.large`, `cache.m5.xlarge`, `cache.m5.2xlarge`, `cache.m5.4xlarge`,
 *     `cache.m5.12xlarge`, `cache.m5.24xlarge`
 *     **M4 node types:**`cache.m4.large`, `cache.m4.xlarge`, `cache.m4.2xlarge`, `cache.m4.4xlarge`,
 *     `cache.m4.10xlarge`
 *     **T3 node types:**`cache.t3.micro`, `cache.t3.small`, `cache.t3.medium`
 *     **T2 node types:**`cache.t2.micro`, `cache.t2.small`, `cache.t2.medium`
 *   - Previous generation: (not recommended)
 *     **T1 node types:**`cache.t1.micro`
 *     **M1 node types:**`cache.m1.small`, `cache.m1.medium`, `cache.m1.large`, `cache.m1.xlarge`
 *     **M3 node types:**`cache.m3.medium`, `cache.m3.large`, `cache.m3.xlarge`, `cache.m3.2xlarge`
 *
 * - Compute optimized:
 *
 *   - Previous generation: (not recommended)
 *     **C1 node types:**`cache.c1.xlarge`
 *
 * - Memory optimized:
 *
 *   - Current generation:
 *     **R6g node types** (available only for Redis engine version 5.0.6 onward and for Memcached engine version 1.5.16
 *     onward).
 *     `cache.r6g.large`, `cache.r6g.xlarge`, `cache.r6g.2xlarge`, `cache.r6g.4xlarge`, `cache.r6g.8xlarge`,
 *     `cache.r6g.12xlarge`, `cache.r6g.16xlarge`
 *
 *     > For region availability, see Supported Node Types
 *
 *     **R5 node types:**`cache.r5.large`, `cache.r5.xlarge`, `cache.r5.2xlarge`, `cache.r5.4xlarge`,
 *     `cache.r5.12xlarge`, `cache.r5.24xlarge`
 *     **R4 node types:**`cache.r4.large`, `cache.r4.xlarge`, `cache.r4.2xlarge`, `cache.r4.4xlarge`,
 *     `cache.r4.8xlarge`, `cache.r4.16xlarge`
 *   - Previous generation: (not recommended)
 *     **M2 node types:**`cache.m2.xlarge`, `cache.m2.2xlarge`, `cache.m2.4xlarge`
 *     **R3 node types:**`cache.r3.large`, `cache.r3.xlarge`, `cache.r3.2xlarge`, `cache.r3.4xlarge`, `cache.r3.8xlarge`
 *
 *
 * **Additional node type info**
 *
 * - All current generation instance types are created in Amazon VPC by default.
 * - Redis append-only files (AOF) are not supported for T1 or T2 instances.
 * - Redis Multi-AZ with automatic failover is not supported on T1 instances.
 * - Redis configuration variables `appendonly` and `appendfsync` are not supported on Redis version 2.8.22 and later.
 *
 * @see https://docs.aws.amazon.com/AmazonElastiCache/latest/red-ug/CacheNodes.SupportedTypes.html#CacheNodes.SupportedTypesByRegion
 * @see https://docs.aws.amazon.com/AmazonElastiCache/latest/red-ug/CacheNodes.SupportedTypes.html#CacheNodes.SupportedTypesByRegion
 */
final class CacheNode
{
    /**
     * The cache node identifier. A node ID is a numeric identifier (0001, 0002, etc.). The combination of cluster ID and
     * node ID uniquely identifies every cache node used in a customer's Amazon account.
     */
    private $cacheNodeId;

    /**
     * The current state of this cache node, one of the following values: `available`, `creating`, `rebooting`, or
     * `deleting`.
     */
    private $cacheNodeStatus;

    /**
     * The date and time when the cache node was created.
     */
    private $cacheNodeCreateTime;

    /**
     * The hostname for connecting to this cache node.
     */
    private $endpoint;

    /**
     * The status of the parameter group applied to this cache node.
     */
    private $parameterGroupStatus;

    /**
     * The ID of the primary node to which this read replica node is synchronized. If this field is empty, this node is not
     * associated with a primary cluster.
     */
    private $sourceCacheNodeId;

    /**
     * The Availability Zone where this node was created and now resides.
     */
    private $customerAvailabilityZone;

    /**
     * The customer outpost ARN of the cache node.
     */
    private $customerOutpostArn;

    /**
     * @param array{
     *   CacheNodeId?: null|string,
     *   CacheNodeStatus?: null|string,
     *   CacheNodeCreateTime?: null|\DateTimeImmutable,
     *   Endpoint?: null|Endpoint|array,
     *   ParameterGroupStatus?: null|string,
     *   SourceCacheNodeId?: null|string,
     *   CustomerAvailabilityZone?: null|string,
     *   CustomerOutpostArn?: null|string,
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
