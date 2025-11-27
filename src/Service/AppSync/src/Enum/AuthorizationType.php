<?php

namespace AsyncAws\AppSync\Enum;

final class AuthorizationType
{
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';
    public const AWS_IAM = 'AWS_IAM';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::AWS_IAM => true,
        ][$value]);
    }
}
