<?php

namespace AsyncAws\CodeDeploy\ValueObject;

use AsyncAws\CodeDeploy\Enum\DeploymentReadyAction;

/**
 * Information about the action to take when newly provisioned instances are ready to receive traffic in a blue/green
 * deployment.
 */
final class DeploymentReadyOption
{
    /**
     * Information about when to reroute traffic from an original environment to a replacement environment in a blue/green
     * deployment.
     */
    private $actionOnTimeout;

    /**
     * The number of minutes to wait before the status of a blue/green deployment is changed to Stopped if rerouting is not
     * started manually. Applies only to the `STOP_DEPLOYMENT` option for `actionOnTimeout`.
     */
    private $waitTimeInMinutes;

    /**
     * @param array{
     *   actionOnTimeout?: null|DeploymentReadyAction::*,
     *   waitTimeInMinutes?: null|int,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->actionOnTimeout = $input['actionOnTimeout'] ?? null;
        $this->waitTimeInMinutes = $input['waitTimeInMinutes'] ?? null;
    }

    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return DeploymentReadyAction::*|null
     */
    public function getActionOnTimeout(): ?string
    {
        return $this->actionOnTimeout;
    }

    public function getWaitTimeInMinutes(): ?int
    {
        return $this->waitTimeInMinutes;
    }
}
