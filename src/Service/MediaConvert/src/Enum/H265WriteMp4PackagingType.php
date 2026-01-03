<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * If the location of parameter set NAL units doesn't matter in your workflow, ignore this setting. Use this setting
 * only with CMAF or DASH outputs, or with standalone file outputs in an MPEG-4 container (MP4 outputs). Choose HVC1 to
 * mark your output as HVC1. This makes your output compliant with the following specification: ISO IECJTC1 SC29 N13798
 * Text ISO/IEC FDIS 14496-15 3rd Edition. For these outputs, the service stores parameter set NAL units in the sample
 * headers but not in the samples directly. For MP4 outputs, when you choose HVC1, your output video might not work
 * properly with some downstream systems and video players. The service defaults to marking your output as HEV1. For
 * these outputs, the service writes parameter set NAL units directly into the samples.
 */
final class H265WriteMp4PackagingType
{
    public const HEV1 = 'HEV1';
    public const HVC1 = 'HVC1';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::HEV1 => true,
            self::HVC1 => true,
        ][$value]);
    }
}
