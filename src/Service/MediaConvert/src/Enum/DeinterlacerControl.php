<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * - When set to NORMAL (default), the deinterlacer does not convert frames that are tagged in metadata as progressive.
 * It will only convert those that are tagged as some other type. - When set to FORCE_ALL_FRAMES, the deinterlacer
 * converts every frame to progressive - even those that are already tagged as progressive. Turn Force mode on only if
 * there is a good chance that the metadata has tagged frames as progressive when they are not progressive. Do not turn
 * on otherwise; processing frames that are already progressive into progressive will probably result in lower quality
 * video.
 */
final class DeinterlacerControl
{
    public const FORCE_ALL_FRAMES = 'FORCE_ALL_FRAMES';
    public const NORMAL = 'NORMAL';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::FORCE_ALL_FRAMES => true,
            self::NORMAL => true,
        ][$value]);
    }
}
