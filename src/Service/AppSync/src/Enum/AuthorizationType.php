<?php

namespace AsyncAws\AppSync\Enum;

/**
 * The authorization type that the HTTP endpoint requires.
 *
 * - **AWS_IAM**: The authorization type is Signature Version 4 (SigV4).
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
