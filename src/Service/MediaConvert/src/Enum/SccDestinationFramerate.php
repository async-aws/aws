<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Set Framerate (SccDestinationFramerate) to make sure that the captions and the video are synchronized in the output.
 * Specify a frame rate that matches the frame rate of the associated video. If the video frame rate is 29.97, choose
 * 29.97 dropframe (FRAMERATE_29_97_DROPFRAME) only if the video has video_insertion=true and drop_frame_timecode=true;
 * otherwise, choose 29.97 non-dropframe (FRAMERATE_29_97_NON_DROPFRAME).
 */
final class SccDestinationFramerate
{
    public const FRAMERATE_23_97 = 'FRAMERATE_23_97';
    public const FRAMERATE_24 = 'FRAMERATE_24';
    public const FRAMERATE_25 = 'FRAMERATE_25';
    public const FRAMERATE_29_97_DROPFRAME = 'FRAMERATE_29_97_DROPFRAME';
    public const FRAMERATE_29_97_NON_DROPFRAME = 'FRAMERATE_29_97_NON_DROPFRAME';

    public static function exists(string $value): bool
    {
        return isset([
            self::FRAMERATE_23_97 => true,
            self::FRAMERATE_24 => true,
            self::FRAMERATE_25 => true,
            self::FRAMERATE_29_97_DROPFRAME => true,
            self::FRAMERATE_29_97_NON_DROPFRAME => true,
        ][$value]);
    }
}
