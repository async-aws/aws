<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Enable this setting to insert the EXT-X-SESSION-KEY element into the master playlist. This allows for offline Apple
 * HLS FairPlay content protection.
 */
final class HlsOfflineEncrypted
{
    public const DISABLED = 'DISABLED';
    public const ENABLED = 'ENABLED';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

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
