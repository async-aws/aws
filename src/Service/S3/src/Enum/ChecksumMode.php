<?php

namespace AsyncAws\S3\Enum;

final class ChecksumMode
{
    public const ENABLED = 'ENABLED';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::ENABLED => true,
        ][$value]);
    }
}
