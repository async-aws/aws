<?php

namespace AsyncAws\CodeDeploy\ValueObject;

/**
 * Information about the Elastic Load Balancing load balancer or target group used in a deployment.
 */
final class LoadBalancerInfo
{
    /**
     * An array that contains information about the load balancer to use for load balancing in a deployment. In Elastic Load
     * Balancing, load balancers are used with Classic Load Balancers.
     *
     * > Adding more than one load balancer to the array is not supported.
     *
     * @var ELBInfo[]|null
     */
    private $elbInfoList;

    /**
     * An array that contains information about the target group to use for load balancing in a deployment. In Elastic Load
     * Balancing, target groups are used with Application Load Balancers.
     *
     * > Adding more than one target group to the array is not supported.
     *
     * @var TargetGroupInfo[]|null
     */
    private $targetGroupInfoList;

    /**
     * The target group pair information. This is an array of `TargeGroupPairInfo` objects with a maximum size of one.
     *
     * @var TargetGroupPairInfo[]|null
     */
    private $targetGroupPairInfoList;

    /**
     * @param array{
     *   elbInfoList?: null|array<ELBInfo|array>,
     *   targetGroupInfoList?: null|array<TargetGroupInfo|array>,
     *   targetGroupPairInfoList?: null|array<TargetGroupPairInfo|array>,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->elbInfoList = isset($input['elbInfoList']) ? array_map([ELBInfo::class, 'create'], $input['elbInfoList']) : null;
        $this->targetGroupInfoList = isset($input['targetGroupInfoList']) ? array_map([TargetGroupInfo::class, 'create'], $input['targetGroupInfoList']) : null;
        $this->targetGroupPairInfoList = isset($input['targetGroupPairInfoList']) ? array_map([TargetGroupPairInfo::class, 'create'], $input['targetGroupPairInfoList']) : null;
    }

    /**
     * @param array{
     *   elbInfoList?: null|array<ELBInfo|array>,
     *   targetGroupInfoList?: null|array<TargetGroupInfo|array>,
     *   targetGroupPairInfoList?: null|array<TargetGroupPairInfo|array>,
     * }|LoadBalancerInfo $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return ELBInfo[]
     */
    public function getElbInfoList(): array
    {
        return $this->elbInfoList ?? [];
    }

    /**
     * @return TargetGroupInfo[]
     */
    public function getTargetGroupInfoList(): array
    {
        return $this->targetGroupInfoList ?? [];
    }

    /**
     * @return TargetGroupPairInfo[]
     */
    public function getTargetGroupPairInfoList(): array
    {
        return $this->targetGroupPairInfoList ?? [];
    }
}
