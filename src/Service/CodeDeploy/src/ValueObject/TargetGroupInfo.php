<?php

namespace AsyncAws\CodeDeploy\ValueObject;

/**
 * Information about a target group in Elastic Load Balancing to use in a deployment. Instances are registered as
 * targets in a target group, and traffic is routed to the target group.
 */
final class TargetGroupInfo
{
    /**
     * For blue/green deployments, the name of the target group that instances in the original environment are deregistered
     * from, and instances in the replacement environment are registered with. For in-place deployments, the name of the
     * target group that instances are deregistered from, so they are not serving traffic during a deployment, and then
     * re-registered with after the deployment is complete.
     *
     * @var string|null
     */
    private $name;

    /**
     * @param array{
     *   name?: string|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->name = $input['name'] ?? null;
    }

    /**
     * @param array{
     *   name?: string|null,
     * }|TargetGroupInfo $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    public function getName(): ?string
    {
        return $this->name;
    }
}
