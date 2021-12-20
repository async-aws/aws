<?php

namespace AsyncAws\CodeDeploy\Enum;

/**
 * The means by which the deployment was created:.
 *
 * - `user`: A user created the deployment.
 * - `autoscaling`: Amazon EC2 Auto Scaling created the deployment.
 * - `codeDeployRollback`: A rollback process created the deployment.
 * - `CodeDeployAutoUpdate`: An auto-update process created the deployment when it detected outdated EC2 instances.
 */
final class DeploymentCreator
{
    public const AUTOSCALING = 'autoscaling';
    public const CLOUD_FORMATION = 'CloudFormation';
    public const CLOUD_FORMATION_ROLLBACK = 'CloudFormationRollback';
    public const CODE_DEPLOY = 'CodeDeploy';
    public const CODE_DEPLOY_AUTO_UPDATE = 'CodeDeployAutoUpdate';
    public const CODE_DEPLOY_ROLLBACK = 'codeDeployRollback';
    public const USER = 'user';

    public static function exists(string $value): bool
    {
        return isset([
            self::AUTOSCALING => true,
            self::CLOUD_FORMATION => true,
            self::CLOUD_FORMATION_ROLLBACK => true,
            self::CODE_DEPLOY => true,
            self::CODE_DEPLOY_AUTO_UPDATE => true,
            self::CODE_DEPLOY_ROLLBACK => true,
            self::USER => true,
        ][$value]);
    }
}
