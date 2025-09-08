<?php

namespace AsyncAws\ElastiCache\ValueObject;

/**
 * Represents a cluster's status within a particular cache security group.
 */
final class CacheSecurityGroupMembership
{
    /**
     * The name of the cache security group.
     *
     * @var string|null
     */
    private $cacheSecurityGroupName;

    /**
     * The membership status in the cache security group. The status changes when a cache security group is modified, or
     * when the cache security groups assigned to a cluster are modified.
     *
     * @var string|null
     */
    private $status;

    /**
     * @param array{
     *   CacheSecurityGroupName?: string|null,
     *   Status?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->cacheSecurityGroupName = $input['CacheSecurityGroupName'] ?? null;
        $this->status = $input['Status'] ?? null;
    }

    /**
     * @param array{
     *   CacheSecurityGroupName?: string|null,
     *   Status?: string|null,
     * }|CacheSecurityGroupMembership $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getCacheSecurityGroupName(): ?string
    {
        return $this->cacheSecurityGroupName;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }
}
