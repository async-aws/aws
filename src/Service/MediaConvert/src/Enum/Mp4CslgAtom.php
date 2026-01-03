<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * When enabled, file composition times will start at zero, composition times in the 'ctts' (composition time to sample)
 * box for B-frames will be negative, and a 'cslg' (composition shift least greatest) box will be included per 14496-1
 * amendment 1. This improves compatibility with Apple players and tools.
 */
final class Mp4CslgAtom
{
    public const EXCLUDE = 'EXCLUDE';
    public const INCLUDE = 'INCLUDE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::EXCLUDE => true,
            self::INCLUDE => true,
        ][$value]);
    }
}
