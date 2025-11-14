<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Choose how MediaConvert handles start and end times for input clipping with video passthrough. Your input video codec
 * must be H.264 or H.265 to use IFRAME. To clip at the nearest IDR-frame: Choose Nearest IDR. If an IDR-frame is not
 * found at the frame that you specify, MediaConvert uses the next compatible IDR-frame. Note that your output may be
 * shorter than your input clip duration. To clip at the nearest I-frame: Choose Nearest I-frame. If an I-frame is not
 * found at the frame that you specify, MediaConvert uses the next compatible I-frame. Note that your output may be
 * shorter than your input clip duration. We only recommend this setting for special workflows, and when you choose this
 * setting your output may not be compatible with most players.
 */
final class FrameControl
{
    public const NEAREST_IDRFRAME = 'NEAREST_IDRFRAME';
    public const NEAREST_IFRAME = 'NEAREST_IFRAME';

    public static function exists(string $value): bool
    {
        return isset([
            self::NEAREST_IDRFRAME => true,
            self::NEAREST_IFRAME => true,
        ][$value]);
    }
}
