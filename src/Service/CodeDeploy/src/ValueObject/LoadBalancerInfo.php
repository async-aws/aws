<?php

namespace AsyncAws\CodeDeploy\ValueObject;

/**
 * Information about the Elastic Load Balancing load balancer or target group used in a deployment.
 *
 * You can use load balancers and target groups in combination. For example, if you have two Classic Load Balancers, and
 * five target groups tied to an Application Load Balancer, you can specify the two Classic Load Balancers in
 * `elbInfoList`, and the five target groups in `targetGroupInfoList`.
 */
final class LoadBalancerInfo
{
    /**
     * An array that contains information about the load balancers to use for load balancing in a deployment. If you're
     * using Classic Load Balancers, specify those load balancers in this array.
     *
     * > You can add up to 10 load balancers to the array.
     *
     * > If you're using Application Load Balancers or Network Load Balancers, use the `targetGroupInfoList` array instead
     * > of this one.
     *
     * @var ELBInfo[]|null
     */
    private $elbInfoList;

    /**
     * An array that contains information about the target groups to use for load balancing in a deployment. If you're using
     * Application Load Balancers and Network Load Balancers, specify their associated target groups in this array.
     *
     * > You can add up to 10 target groups to the array.
     *
     * > If you're using Classic Load Balancers, use the `elbInfoList` array instead of this one.
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
     *   elbInfoList?: array<ELBInfo|array>|null,
     *   targetGroupInfoList?: array<TargetGroupInfo|array>|null,
     *   targetGroupPairInfoList?: array<TargetGroupPairInfo|array>|null,
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
     *   elbInfoList?: array<ELBInfo|array>|null,
     *   targetGroupInfoList?: array<TargetGroupInfo|array>|null,
     *   targetGroupPairInfoList?: array<TargetGroupPairInfo|array>|null,
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
