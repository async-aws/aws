<?php

namespace AsyncAws\AppSync\Enum;

/**
 * The authorization type required by the HTTP endpoint.
 *
 * - **AWS_IAM**: The authorization type is Sigv4.
 */
final class AuthorizationType
{
    public const AWS_IAM = 'AWS_IAM';

    public static function exists(string $value): bool
    {
        return isset([
            self::AWS_IAM => true,
        ][$value]);
    }
}
