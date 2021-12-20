<?php

namespace AsyncAws\CodeDeploy\Enum;

/**
 * Information about when to reroute traffic from an original environment to a replacement environment in a blue/green
 * deployment.
 *
 * - CONTINUE_DEPLOYMENT: Register new instances with the load balancer immediately after the new application revision
 *   is installed on the instances in the replacement environment.
 * - STOP_DEPLOYMENT: Do not register new instances with a load balancer unless traffic rerouting is started using
 *   ContinueDeployment. If traffic rerouting is not started before the end of the specified wait period, the deployment
 *   status is changed to Stopped.
 */
final class DeploymentReadyAction
{
    public const CONTINUE_DEPLOYMENT = 'CONTINUE_DEPLOYMENT';
    public const STOP_DEPLOYMENT = 'STOP_DEPLOYMENT';

    public static function exists(string $value): bool
    {
        return isset([
            self::CONTINUE_DEPLOYMENT => true,
            self::STOP_DEPLOYMENT => true,
        ][$value]);
    }
}
