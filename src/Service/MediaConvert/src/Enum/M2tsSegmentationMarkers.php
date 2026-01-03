<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Inserts segmentation markers at each segmentation_time period. rai_segstart sets the Random Access Indicator bit in
 * the adaptation field. rai_adapt sets the RAI bit and adds the current timecode in the private data bytes.
 * psi_segstart inserts PAT and PMT tables at the start of segments. ebp adds Encoder Boundary Point information to the
 * adaptation field as per OpenCable specification OC-SP-EBP-I01-130118. ebp_legacy adds Encoder Boundary Point
 * information to the adaptation field using a legacy proprietary format.
 */
final class M2tsSegmentationMarkers
{
    public const EBP = 'EBP';
    public const EBP_LEGACY = 'EBP_LEGACY';
    public const NONE = 'NONE';
    public const PSI_SEGSTART = 'PSI_SEGSTART';
    public const RAI_ADAPT = 'RAI_ADAPT';
    public const RAI_SEGSTART = 'RAI_SEGSTART';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::EBP => true,
            self::EBP_LEGACY => true,
            self::NONE => true,
            self::PSI_SEGSTART => true,
            self::RAI_ADAPT => true,
            self::RAI_SEGSTART => true,
        ][$value]);
    }
}
