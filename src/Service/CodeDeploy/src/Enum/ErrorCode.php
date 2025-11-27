<?php

namespace AsyncAws\CodeDeploy\Enum;

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
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
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
