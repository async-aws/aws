<?php

namespace AsyncAws\CodeDeploy\ValueObject;

use AsyncAws\CodeDeploy\Enum\DeploymentReadyAction;

/**
 * Information about how traffic is rerouted to instances in a replacement environment in a blue/green deployment.
 */
final class DeploymentReadyOption
{
    /**
     * Information about when to reroute traffic from an original environment to a replacement environment in a blue/green
     * deployment.
     *
     * - CONTINUE_DEPLOYMENT: Register new instances with the load balancer immediately after the new application revision
     *   is installed on the instances in the replacement environment.
     * - STOP_DEPLOYMENT: Do not register new instances with a load balancer unless traffic rerouting is started using
     *   ContinueDeployment. If traffic rerouting is not started before the end of the specified wait period, the deployment
     *   status is changed to Stopped.
     *
     * @var DeploymentReadyAction::*|string|null
     */
    private $actionOnTimeout;

    /**
     * The number of minutes to wait before the status of a blue/green deployment is changed to Stopped if rerouting is not
     * started manually. Applies only to the `STOP_DEPLOYMENT` option for `actionOnTimeout`.
     *
     * @var int|null
     */
    private $waitTimeInMinutes;

    /**
     * @param array{
     *   actionOnTimeout?: null|DeploymentReadyAction::*|string,
     *   waitTimeInMinutes?: null|int,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->actionOnTimeout = $input['actionOnTimeout'] ?? null;
        $this->waitTimeInMinutes = $input['waitTimeInMinutes'] ?? null;
    }

    /**
     * @param array{
     *   actionOnTimeout?: null|DeploymentReadyAction::*|string,
     *   waitTimeInMinutes?: null|int,
     * }|DeploymentReadyOption $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return DeploymentReadyAction::*|string|null
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
