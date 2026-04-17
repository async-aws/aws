<?php

namespace AsyncAws\ImageBuilder\Enum;

final class BuildType
{
    public const IMPORT = 'IMPORT';
    public const IMPORT_ISO = 'IMPORT_ISO';
    public const SCHEDULED = 'SCHEDULED';
    public const USER_INITIATED = 'USER_INITIATED';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::IMPORT => true,
            self::IMPORT_ISO => true,
            self::SCHEDULED => true,
            self::USER_INITIATED => true,
        ][$value]);
    }
}
