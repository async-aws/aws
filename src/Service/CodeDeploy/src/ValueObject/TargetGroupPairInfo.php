<?php

namespace AsyncAws\CodeDeploy\ValueObject;

/**
 * Information about two target groups and how traffic is routed during an Amazon ECS deployment. An optional test
 * traffic route can be specified.
 */
final class TargetGroupPairInfo
{
    /**
     * One pair of target groups. One is associated with the original task set. The second is associated with the task set
     * that serves traffic after the deployment is complete.
     */
    private $targetGroups;

    /**
     * The path used by a load balancer to route production traffic when an Amazon ECS deployment is complete.
     */
    private $prodTrafficRoute;

    /**
     * An optional path used by a load balancer to route test traffic after an Amazon ECS deployment. Validation can occur
     * while test traffic is served during a deployment.
     */
    private $testTrafficRoute;

    /**
     * @param array{
     *   targetGroups?: null|TargetGroupInfo[],
     *   prodTrafficRoute?: null|TrafficRoute|array,
     *   testTrafficRoute?: null|TrafficRoute|array,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->targetGroups = isset($input['targetGroups']) ? array_map([TargetGroupInfo::class, 'create'], $input['targetGroups']) : null;
        $this->prodTrafficRoute = isset($input['prodTrafficRoute']) ? TrafficRoute::create($input['prodTrafficRoute']) : null;
        $this->testTrafficRoute = isset($input['testTrafficRoute']) ? TrafficRoute::create($input['testTrafficRoute']) : null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getProdTrafficRoute(): ?TrafficRoute
    {
        return $this->prodTrafficRoute;
    }

    /**
     * @return TargetGroupInfo[]
     */
    public function getTargetGroups(): array
    {
        return $this->targetGroups ?? [];
    }

    public function getTestTrafficRoute(): ?TrafficRoute
    {
        return $this->testTrafficRoute;
    }
}
