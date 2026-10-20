<?php

namespace AsyncAws\CodeDeploy\Exception;

use AsyncAws\Core\Exception\Http\ClientException;

/**
 * The `autoScalingGroupProcessesToSuspend` list in `ec2DeploymentOptions` contains a process that is not valid. Valid
 * values are `Terminate`, `AZRebalance`, `InstanceRefresh`, and `ScheduledActions`. Process names are case sensitive.
 */
final class InvalidAutoScalingGroupProcessToSuspendException extends ClientException
{
}
