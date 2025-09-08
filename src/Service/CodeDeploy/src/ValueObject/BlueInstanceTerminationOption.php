<?php

namespace AsyncAws\CodeDeploy\ValueObject;

use AsyncAws\CodeDeploy\Enum\InstanceAction;

/**
 * Information about whether instances in the original environment are terminated when a blue/green deployment is
 * successful. `BlueInstanceTerminationOption` does not apply to Lambda deployments.
 */
final class BlueInstanceTerminationOption
{
    /**
     * The action to take on instances in the original environment after a successful blue/green deployment.
     *
     * - `TERMINATE`: Instances are terminated after a specified wait time.
     * - `KEEP_ALIVE`: Instances are left running after they are deregistered from the load balancer and removed from the
     *   deployment group.
     *
     * @var InstanceAction::*|null
     */
    private $action;

    /**
     * For an Amazon EC2 deployment, the number of minutes to wait after a successful blue/green deployment before
     * terminating instances from the original environment.
     *
     * For an Amazon ECS deployment, the number of minutes before deleting the original (blue) task set. During an Amazon
     * ECS deployment, CodeDeploy shifts traffic from the original (blue) task set to a replacement (green) task set.
     *
     * The maximum setting is 2880 minutes (2 days).
     *
     * @var int|null
     */
    private $terminationWaitTimeInMinutes;

    /**
     * @param array{
     *   action?: InstanceAction::*|null,
     *   terminationWaitTimeInMinutes?: int|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->action = $input['action'] ?? null;
        $this->terminationWaitTimeInMinutes = $input['terminationWaitTimeInMinutes'] ?? null;
    }

    /**
     * @param array{
     *   action?: InstanceAction::*|null,
     *   terminationWaitTimeInMinutes?: int|null,
     * }|BlueInstanceTerminationOption $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return InstanceAction::*|null
     */
    public function getAction(): ?string
    {
        return $this->action;
    }

    public function getTerminationWaitTimeInMinutes(): ?int
    {
        return $this->terminationWaitTimeInMinutes;
    }
}
