<?php

namespace AsyncAws\BedrockAgent\Enum;

final class CustomSourceType
{
    public const IN_LINE = 'IN_LINE';
    public const S3_LOCATION = 'S3_LOCATION';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::IN_LINE => true,
            self::S3_LOCATION => true,
        ][$value]);
    }
}
