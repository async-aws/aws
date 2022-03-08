<?php

namespace AsyncAws\CodeBuild\ValueObject;

/**
 * If your CodeBuild project accesses resources in an Amazon VPC, you provide this parameter that identifies the VPC ID
 * and the list of security group IDs and subnet IDs. The security groups and subnets must belong to the same VPC. You
 * must provide at least one security group and one subnet ID.
 */
final class VpcConfig
{
    /**
     * The ID of the Amazon VPC.
     */
    private $vpcId;

    /**
     * A list of one or more subnet IDs in your Amazon VPC.
     */
    private $subnets;

    /**
     * A list of one or more security groups IDs in your Amazon VPC.
     */
    private $securityGroupIds;

    /**
     * @param array{
     *   vpcId?: null|string,
     *   subnets?: null|string[],
     *   securityGroupIds?: null|string[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->vpcId = $input['vpcId'] ?? null;
        $this->subnets = $input['subnets'] ?? null;
        $this->securityGroupIds = $input['securityGroupIds'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
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
    public function getSubnets(): array
    {
        return $this->subnets ?? [];
    }

    public function getVpcId(): ?string
    {
        return $this->vpcId;
    }
}
