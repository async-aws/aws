<?php

namespace AsyncAws\Lambda\ValueObject;

/**
 * The function's networking configuration.
 */
final class VpcConfigResponse
{
    /**
     * A list of VPC subnet IDs.
     */
    private $subnetIds;

    /**
     * A list of VPC security groups IDs.
     */
    private $securityGroupIds;

    /**
     * The ID of the VPC.
     */
    private $vpcId;

    /**
     * @param array{
     *   SubnetIds?: null|string[],
     *   SecurityGroupIds?: null|string[],
     *   VpcId?: null|string,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->subnetIds = $input['SubnetIds'] ?? null;
        $this->securityGroupIds = $input['SecurityGroupIds'] ?? null;
        $this->vpcId = $input['VpcId'] ?? null;
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
    public function getSubnetIds(): array
    {
        return $this->subnetIds ?? [];
    }

    public function getVpcId(): ?string
    {
        return $this->vpcId;
    }
}
