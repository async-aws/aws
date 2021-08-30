<?php

namespace AsyncAws\ElastiCache\ValueObject;

/**
 * Represents a single cache security group and its status.
 */
final class SecurityGroupMembership
{
    /**
     * The identifier of the cache security group.
     */
    private $securityGroupId;

    /**
     * The status of the cache security group membership. The status changes whenever a cache security group is modified, or
     * when the cache security groups assigned to a cluster are modified.
     */
    private $status;

    /**
     * @param array{
     *   SecurityGroupId?: null|string,
     *   Status?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->securityGroupId = $input['SecurityGroupId'] ?? null;
        $this->status = $input['Status'] ?? null;
    }

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
