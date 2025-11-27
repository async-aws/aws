<?php

namespace AsyncAws\Rekognition\Enum;

final class ProjectAutoUpdate
{
    public const DISABLED = 'DISABLED';
    public const ENABLED = 'ENABLED';

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
