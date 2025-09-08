<?php

namespace AsyncAws\Lambda\ValueObject;

/**
 * The VPC security groups and subnets that are attached to a Lambda function.
 */
final class VpcConfigResponse
{
    /**
     * A list of VPC subnet IDs.
     *
     * @var string[]|null
     */
    private $subnetIds;

    /**
     * A list of VPC security group IDs.
     *
     * @var string[]|null
     */
    private $securityGroupIds;

    /**
     * The ID of the VPC.
     *
     * @var string|null
     */
    private $vpcId;

    /**
     * Allows outbound IPv6 traffic on VPC functions that are connected to dual-stack subnets.
     *
     * @var bool|null
     */
    private $ipv6AllowedForDualStack;

    /**
     * @param array{
     *   SubnetIds?: string[]|null,
     *   SecurityGroupIds?: string[]|null,
     *   VpcId?: string|null,
     *   Ipv6AllowedForDualStack?: bool|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->subnetIds = $input['SubnetIds'] ?? null;
        $this->securityGroupIds = $input['SecurityGroupIds'] ?? null;
        $this->vpcId = $input['VpcId'] ?? null;
        $this->ipv6AllowedForDualStack = $input['Ipv6AllowedForDualStack'] ?? null;
    }

    /**
     * @param array{
     *   SubnetIds?: string[]|null,
     *   SecurityGroupIds?: string[]|null,
     *   VpcId?: string|null,
     *   Ipv6AllowedForDualStack?: bool|null,
     * }|VpcConfigResponse $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getIpv6AllowedForDualStack(): ?bool
    {
        return $this->ipv6AllowedForDualStack;
    }

    /**
     * @return string[]
     */
    public function getSecurityGroupIds(): array
    {
        return $this->securityGroupIds ?? [];
    }

    /**
     * @return string[]
     */
    public function getSubnetIds(): array
    {
        return $this->subnetIds ?? [];
    }

    public function getVpcId(): ?string
    {
        return $this->vpcId;
    }
}
