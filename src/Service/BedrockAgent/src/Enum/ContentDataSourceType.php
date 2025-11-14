<?php

namespace AsyncAws\BedrockAgent\Enum;

final class ContentDataSourceType
{
    public const CUSTOM = 'CUSTOM';
    public const S3 = 'S3';

    public static function exists(string $value): bool
    {
        return isset([
            self::CUSTOM => true,
            self::S3 => true,
        ][$value]);
    }
}
