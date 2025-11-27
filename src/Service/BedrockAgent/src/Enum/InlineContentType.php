<?php

namespace AsyncAws\BedrockAgent\Enum;

final class InlineContentType
{
    public const BYTE = 'BYTE';
    public const TEXT = 'TEXT';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::BYTE => true,
            self::TEXT => true,
        ][$value]);
    }
}
