<?php

namespace AsyncAws\Lambda\ValueObject;

/**
 * The VPC security groups and subnets that are attached to a Lambda function. For more information, see Configuring a
 * Lambda function to access resources in a VPC [^1].
 *
 * [^1]: https://docs.aws.amazon.com/lambda/latest/dg/configuration-vpc.html
 */
final class VpcConfig
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
     * @param array{
     *   SubnetIds?: null|string[],
     *   SecurityGroupIds?: null|string[],
     * } $input
     */
    public function __construct(array $input)
    {
        $this->subnetIds = $input['SubnetIds'] ?? null;
        $this->securityGroupIds = $input['SecurityGroupIds'] ?? null;
    }

    /**
     * @param array{
     *   SubnetIds?: null|string[],
     *   SecurityGroupIds?: null|string[],
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
    public function getSubnetIds(): array
    {
        return $this->subnetIds ?? [];
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->subnetIds) {
            $index = -1;
            $payload['SubnetIds'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['SubnetIds'][$index] = $listValue;
            }
        }
        if (null !== $v = $this->securityGroupIds) {
            $index = -1;
            $payload['SecurityGroupIds'] = [];
            foreach ($v as $listValue) {
                ++$index;
                $payload['SecurityGroupIds'][$index] = $listValue;
            }
        }

        return $payload;
    }
}
