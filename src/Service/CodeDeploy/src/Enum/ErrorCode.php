<?php

namespace AsyncAws\CodeDeploy\Enum;

/**
 * For more information, see Error Codes for AWS CodeDeploy in the AWS CodeDeploy User Guide.
 * The error code:.
 *
 * - APPLICATION_MISSING: The application was missing. This error code is most likely raised if the application is
 *   deleted after the deployment is created, but before it is started.
 * - DEPLOYMENT_GROUP_MISSING: The deployment group was missing. This error code is most likely raised if the deployment
 *   group is deleted after the deployment is created, but before it is started.
 * - HEALTH_CONSTRAINTS: The deployment failed on too many instances to be successfully deployed within the instance
 *   health constraints specified.
 * - HEALTH_CONSTRAINTS_INVALID: The revision cannot be successfully deployed within the instance health constraints
 *   specified.
 * - IAM_ROLE_MISSING: The service role cannot be accessed.
 * - IAM_ROLE_PERMISSIONS: The service role does not have the correct permissions.
 * - INTERNAL_ERROR: There was an internal error.
 * - NO_EC2_SUBSCRIPTION: The calling account is not subscribed to Amazon EC2.
 * - NO_INSTANCES: No instances were specified, or no instances can be found.
 * - OVER_MAX_INSTANCES: The maximum number of instances was exceeded.
 * - THROTTLED: The operation was throttled because the calling account exceeded the throttling limits of one or more
 *   AWS services.
 * - TIMEOUT: The deployment has timed out.
 * - REVISION_MISSING: The revision ID was missing. This error code is most likely raised if the revision is deleted
 *   after the deployment is created, but before it is started.
 *
 * @see https://docs.aws.amazon.com/codedeploy/latest/userguide/error-codes.html
 * @see https://docs.aws.amazon.com/codedeploy/latest/userguide
 */
final class ErrorCode
{
    public const AGENT_ISSUE = 'AGENT_ISSUE';
    public const ALARM_ACTIVE = 'ALARM_ACTIVE';
    public const APPLICATION_MISSING = 'APPLICATION_MISSING';
    public const AUTOSCALING_VALIDATION_ERROR = 'AUTOSCALING_VALIDATION_ERROR';
    public const AUTO_SCALING_CONFIGURATION = 'AUTO_SCALING_CONFIGURATION';
    public const AUTO_SCALING_IAM_ROLE_PERMISSIONS = 'AUTO_SCALING_IAM_ROLE_PERMISSIONS';
    public const CLOUDFORMATION_STACK_FAILURE = 'CLOUDFORMATION_STACK_FAILURE';
    public const CODEDEPLOY_RESOURCE_CANNOT_BE_FOUND = 'CODEDEPLOY_RESOURCE_CANNOT_BE_FOUND';
    public const CUSTOMER_APPLICATION_UNHEALTHY = 'CUSTOMER_APPLICATION_UNHEALTHY';
    public const DEPLOYMENT_GROUP_MISSING = 'DEPLOYMENT_GROUP_MISSING';
    public const ECS_UPDATE_ERROR = 'ECS_UPDATE_ERROR';
    public const ELASTIC_LOAD_BALANCING_INVALID = 'ELASTIC_LOAD_BALANCING_INVALID';
    public const ELB_INVALID_INSTANCE = 'ELB_INVALID_INSTANCE';
    public const HEALTH_CONSTRAINTS = 'HEALTH_CONSTRAINTS';
    public const HEALTH_CONSTRAINTS_INVALID = 'HEALTH_CONSTRAINTS_INVALID';
    public const HOOK_EXECUTION_FAILURE = 'HOOK_EXECUTION_FAILURE';
    public const IAM_ROLE_MISSING = 'IAM_ROLE_MISSING';
    public const IAM_ROLE_PERMISSIONS = 'IAM_ROLE_PERMISSIONS';
    public const INTERNAL_ERROR = 'INTERNAL_ERROR';
    public const INVALID_ECS_SERVICE = 'INVALID_ECS_SERVICE';
    public const INVALID_LAMBDA_CONFIGURATION = 'INVALID_LAMBDA_CONFIGURATION';
    public const INVALID_LAMBDA_FUNCTION = 'INVALID_LAMBDA_FUNCTION';
    public const INVALID_REVISION = 'INVALID_REVISION';
    public const MANUAL_STOP = 'MANUAL_STOP';
    public const MISSING_BLUE_GREEN_DEPLOYMENT_CONFIGURATION = 'MISSING_BLUE_GREEN_DEPLOYMENT_CONFIGURATION';
    public const MISSING_ELB_INFORMATION = 'MISSING_ELB_INFORMATION';
    public const MISSING_GITHUB_TOKEN = 'MISSING_GITHUB_TOKEN';
    public const NO_EC2_SUBSCRIPTION = 'NO_EC2_SUBSCRIPTION';
    public const NO_INSTANCES = 'NO_INSTANCES';
    public const OVER_MAX_INSTANCES = 'OVER_MAX_INSTANCES';
    public const RESOURCE_LIMIT_EXCEEDED = 'RESOURCE_LIMIT_EXCEEDED';
    public const REVISION_MISSING = 'REVISION_MISSING';
    public const THROTTLED = 'THROTTLED';
    public const TIMEOUT = 'TIMEOUT';

    public static function exists(string $value): bool
    {
        return isset([
            self::AGENT_ISSUE => true,
            self::ALARM_ACTIVE => true,
            self::APPLICATION_MISSING => true,
            self::AUTOSCALING_VALIDATION_ERROR => true,
            self::AUTO_SCALING_CONFIGURATION => true,
            self::AUTO_SCALING_IAM_ROLE_PERMISSIONS => true,
            self::CLOUDFORMATION_STACK_FAILURE => true,
            self::CODEDEPLOY_RESOURCE_CANNOT_BE_FOUND => true,
            self::CUSTOMER_APPLICATION_UNHEALTHY => true,
            self::DEPLOYMENT_GROUP_MISSING => true,
            self::ECS_UPDATE_ERROR => true,
            self::ELASTIC_LOAD_BALANCING_INVALID => true,
            self::ELB_INVALID_INSTANCE => true,
            self::HEALTH_CONSTRAINTS => true,
            self::HEALTH_CONSTRAINTS_INVALID => true,
            self::HOOK_EXECUTION_FAILURE => true,
            self::IAM_ROLE_MISSING => true,
            self::IAM_ROLE_PERMISSIONS => true,
            self::INTERNAL_ERROR => true,
            self::INVALID_ECS_SERVICE => true,
            self::INVALID_LAMBDA_CONFIGURATION => true,
            self::INVALID_LAMBDA_FUNCTION => true,
            self::INVALID_REVISION => true,
            self::MANUAL_STOP => true,
            self::MISSING_BLUE_GREEN_DEPLOYMENT_CONFIGURATION => true,
            self::MISSING_ELB_INFORMATION => true,
            self::MISSING_GITHUB_TOKEN => true,
            self::NO_EC2_SUBSCRIPTION => true,
            self::NO_INSTANCES => true,
            self::OVER_MAX_INSTANCES => true,
            self::RESOURCE_LIMIT_EXCEEDED => true,
            self::REVISION_MISSING => true,
            self::THROTTLED => true,
            self::TIMEOUT => true,
        ][$value]);
    }
}
