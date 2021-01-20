<?php

namespace AsyncAws\Ssm\Enum;

/**
 * The type of parameter. Valid values include the following: `String`, `StringList`, and `SecureString`.
 */
final class ParameterType
{
    public const SECURE_STRING = 'SecureString';
    public const STRING = 'String';
    public const STRING_LIST = 'StringList';

    public static function exists(string $value): bool
    {
        return isset([
            self::SECURE_STRING => true,
            self::STRING => true,
            self::STRING_LIST => true,
        ][$value]);
    }
}
