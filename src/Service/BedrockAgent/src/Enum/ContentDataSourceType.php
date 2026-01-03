<?php

namespace AsyncAws\BedrockAgent\Enum;

final class ContentDataSourceType
{
    public const CUSTOM = 'CUSTOM';
    public const S3 = 'S3';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::CUSTOM => true,
            self::S3 => true,
        ][$value]);
    }
}
