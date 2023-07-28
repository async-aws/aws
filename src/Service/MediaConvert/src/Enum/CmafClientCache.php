<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Disable this setting only when your workflow requires the #EXT-X-ALLOW-CACHE:no tag. Otherwise, keep the default
 * value Enabled and control caching in your video distribution set up. For example, use the Cache-Control http header.
 */
final class CmafClientCache
{
    public const DISABLED = 'DISABLED';
    public const ENABLED = 'ENABLED';

    public static function exists(string $value): bool
    {
        return isset([
            self::DISABLED => true,
            self::ENABLED => true,
        ][$value]);
    }
}
