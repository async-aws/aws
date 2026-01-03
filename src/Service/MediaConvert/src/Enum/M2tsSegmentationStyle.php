<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * The segmentation style parameter controls how segmentation markers are inserted into the transport stream. With
 * avails, it is possible that segments may be truncated, which can influence where future segmentation markers are
 * inserted. When a segmentation style of "reset_cadence" is selected and a segment is truncated due to an avail, we
 * will reset the segmentation cadence. This means the subsequent segment will have a duration of of $segmentation_time
 * seconds. When a segmentation style of "maintain_cadence" is selected and a segment is truncated due to an avail, we
 * will not reset the segmentation cadence. This means the subsequent segment will likely be truncated as well. However,
 * all segments after that will have a duration of $segmentation_time seconds. Note that EBP lookahead is a slight
 * exception to this rule.
 */
final class M2tsSegmentationStyle
{
    public const MAINTAIN_CADENCE = 'MAINTAIN_CADENCE';
    public const RESET_CADENCE = 'RESET_CADENCE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::MAINTAIN_CADENCE => true,
            self::RESET_CADENCE => true,
        ][$value]);
    }
}
