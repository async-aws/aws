<?php

namespace AsyncAws\ElastiCache\ValueObject;

/**
 * Represents a single cache security group and its status.
 */
final class SecurityGroupMembership
{
    /**
     * The identifier of the cache security group.
     *
     * @var string|null
     */
    private $securityGroupId;

    /**
     * The status of the cache security group membership. The status changes whenever a cache security group is modified, or
     * when the cache security groups assigned to a cluster are modified.
     *
     * @var string|null
     */
    private $status;

    /**
     * @param array{
     *   SecurityGroupId?: string|null,
     *   Status?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->securityGroupId = $input['SecurityGroupId'] ?? null;
        $this->status = $input['Status'] ?? null;
    }

    /**
     * @param array{
     *   SecurityGroupId?: string|null,
     *   Status?: string|null,
     * }|SecurityGroupMembership $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getSecurityGroupId(): ?string
    {
        return $this->securityGroupId;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }
}
