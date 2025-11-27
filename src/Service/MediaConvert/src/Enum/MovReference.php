<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Always keep the default value (SELF_CONTAINED) for this setting.
 */
final class MovReference
{
    public const EXTERNAL = 'EXTERNAL';
    public const SELF_CONTAINED = 'SELF_CONTAINED';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::EXTERNAL => true,
            self::SELF_CONTAINED => true,
        ][$value]);
    }
}
