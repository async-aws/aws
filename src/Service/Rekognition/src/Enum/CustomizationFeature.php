<?php

namespace AsyncAws\Rekognition\Enum;

final class CustomizationFeature
{
    public const CONTENT_MODERATION = 'CONTENT_MODERATION';
    public const CUSTOM_LABELS = 'CUSTOM_LABELS';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::CONTENT_MODERATION => true,
            self::CUSTOM_LABELS => true,
        ][$value]);
    }
}
