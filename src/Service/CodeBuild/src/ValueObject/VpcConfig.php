<?php

namespace AsyncAws\CodeBuild\ValueObject;

/**
 * Information about the VPC configuration that CodeBuild accesses.
 */
final class VpcConfig
{
    /**
     * The ID of the Amazon VPC.
     *
     * @var string|null
     */
    private $vpcId;

    /**
     * A list of one or more subnet IDs in your Amazon VPC.
     *
     * @var string[]|null
     */
    private $subnets;

    /**
     * A list of one or more security groups IDs in your Amazon VPC.
     *
     * @var string[]|null
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

    /**
     * @param array{
     *   vpcId?: null|string,
     *   subnets?: null|string[],
     *   securityGroupIds?: null|string[],
     * }|VpcConfig $input
     */
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
