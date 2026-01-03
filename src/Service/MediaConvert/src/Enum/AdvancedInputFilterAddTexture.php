<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Add texture and detail to areas of your input video content that were lost after applying the Advanced input filter.
 * To adaptively add texture and reduce softness: Choose Enabled. To not add any texture: Keep the default value,
 * Disabled. We recommend that you choose Disabled for input video content that doesn't have texture, including screen
 * recordings, computer graphics, or cartoons.
 */
final class AdvancedInputFilterAddTexture
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
