<?php

namespace AsyncAws\CodeDeploy\Enum;

/**
 * The type of application revision:.
 *
 * - S3: An application revision stored in Amazon S3.
 * - GitHub: An application revision stored in GitHub (EC2/On-premises deployments only).
 * - String: A YAML-formatted or JSON-formatted string (AWS Lambda deployments only).
 * - AppSpecContent: An `AppSpecContent` object that contains the contents of an AppSpec file for an AWS Lambda or
 *   Amazon ECS deployment. The content is formatted as JSON or YAML stored as a RawString.
 */
final class RevisionLocationType
{
    public const APP_SPEC_CONTENT = 'AppSpecContent';
    public const GIT_HUB = 'GitHub';
    public const S3 = 'S3';
    public const STRING = 'String';

    public static function exists(string $value): bool
    {
        return isset([
            self::APP_SPEC_CONTENT => true,
            self::GIT_HUB => true,
            self::S3 => true,
            self::STRING => true,
        ][$value]);
    }
}
