<?php

namespace AsyncAws\S3\Enum;

final class MFADelete
{
    public const DISABLED = 'Disabled';
    public const ENABLED = 'Enabled';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::DISABLED => true,
            self::ENABLED => true,
        ][$value]);
    }
}
