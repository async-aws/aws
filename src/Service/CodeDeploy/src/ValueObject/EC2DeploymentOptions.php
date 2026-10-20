<?php

namespace AsyncAws\CodeDeploy\ValueObject;

use AsyncAws\CodeDeploy\Enum\AutoScalingGroupProcess;
use AsyncAws\Core\Exception\InvalidArgument;

/**
 * Options that control how CodeDeploy manages Amazon EC2 Auto Scaling groups during a deployment.
 */
final class EC2DeploymentOptions
{
    /**
     * The Amazon EC2 Auto Scaling processes to suspend during the deployment. CodeDeploy suspends these processes before it
     * deploys to instances in an Auto Scaling group, and resumes them when the deployment succeeds, fails, or is stopped.
     * Valid values are `Terminate`, `AZRebalance`, `InstanceRefresh`, and `ScheduledActions`.
     *
     * This parameter applies only to in-place deployments on the EC2/On-premises compute platform. If the list is empty, or
     * if the deployment does not target instances in an Auto Scaling group, no processes are suspended.
     *
     * The service role for the deployment group must have permission to call `autoscaling:SuspendProcesses` and
     * `autoscaling:ResumeProcesses` on the target Auto Scaling groups. If the processes cannot be suspended, the deployment
     * fails before any instance is updated.
     *
     * If you suspend `ScheduledActions`, scheduled actions with a start time during the deployment are skipped and do not
     * run after the process resumes.
     *
     * > If CodeDeploy cannot resume the processes, the deployment fails and the processes stay suspended until you resume
     * > them with the Auto Scaling `ResumeProcesses` action. While `Terminate` is suspended, Auto Scaling does not replace
     * > unhealthy instances. If you suspend `Terminate`, configure a trigger that notifies you when a deployment fails.
     *
     * @var list<AutoScalingGroupProcess::*>|null
     */
    private $autoScalingGroupProcessesToSuspend;

    /**
     * @param array{
     *   autoScalingGroupProcessesToSuspend?: array<AutoScalingGroupProcess::*>|null,
     * } $input
     */
    public function __construct(array $input)
    {
        $this->autoScalingGroupProcessesToSuspend = $input['autoScalingGroupProcessesToSuspend'] ?? null;
    }

    /**
     * @param array{
     *   autoScalingGroupProcessesToSuspend?: array<AutoScalingGroupProcess::*>|null,
     * }|EC2DeploymentOptions $input
     */
    public static function create($input): self
    {
        return $input instanceof self ? $input : new self($input);
    }

    /**
     * @return list<AutoScalingGroupProcess::*>
     */
    public function getAutoScalingGroupProcessesToSuspend(): array
    {
        return $this->autoScalingGroupProcessesToSuspend ?? [];
    }

    /**
     * @internal
     */
    public function requestBody(): array
    {
        $payload = [];
        if (null !== $v = $this->autoScalingGroupProcessesToSuspend) {
            $index = -1;
            $payload['autoScalingGroupProcessesToSuspend'] = [];
            foreach ($v as $listValue) {
                ++$index;
                if (!AutoScalingGroupProcess::exists($listValue)) {
                    /** @psalm-suppress NoValue */
                    throw new InvalidArgument(\sprintf('Invalid parameter "autoScalingGroupProcessesToSuspend" for "%s". The value "%s" is not a valid "AutoScalingGroupProcess".', __CLASS__, $listValue));
                }
                $payload['autoScalingGroupProcessesToSuspend'][$index] = $listValue;
            }
        }

        return $payload;
    }
}
