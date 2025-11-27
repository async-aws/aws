<?php

namespace AsyncAws\BedrockAgent\Enum;

final class MetadataSourceType
{
    public const IN_LINE_ATTRIBUTE = 'IN_LINE_ATTRIBUTE';
    public const S3_LOCATION = 'S3_LOCATION';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::IN_LINE_ATTRIBUTE => true,
            self::S3_LOCATION => true,
        ][$value]);
    }
}
