<?php

namespace AsyncAws\Ssm\Enum;

final class ParameterType
{
    public const SECURE_STRING = 'SecureString';
    public const STRING = 'String';
    public const STRING_LIST = 'StringList';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::SECURE_STRING => true,
            self::STRING => true,
            self::STRING_LIST => true,
        ][$value]);
    }
}
