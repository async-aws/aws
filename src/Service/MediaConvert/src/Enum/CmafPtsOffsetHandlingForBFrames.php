<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Use this setting only when your output video stream has B-frames, which causes the initial presentation time stamp
 * (PTS) to be offset from the initial decode time stamp (DTS). Specify how MediaConvert handles PTS when writing time
 * stamps in output DASH manifests. Choose Match initial PTS when you want MediaConvert to use the initial PTS as the
 * first time stamp in the manifest. Choose Zero-based to have MediaConvert ignore the initial PTS in the video stream
 * and instead write the initial time stamp as zero in the manifest. For outputs that don't have B-frames, the time
 * stamps in your DASH manifests start at zero regardless of your choice here.
 */
final class CmafPtsOffsetHandlingForBFrames
{
    public const MATCH_INITIAL_PTS = 'MATCH_INITIAL_PTS';
    public const ZERO_BASED = 'ZERO_BASED';

    public static function exists(string $value): bool
    {
        return isset([
            self::MATCH_INITIAL_PTS => true,
            self::ZERO_BASED => true,
        ][$value]);
    }
}
