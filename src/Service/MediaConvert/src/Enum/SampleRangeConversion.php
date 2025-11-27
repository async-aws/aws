<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify how MediaConvert limits the color sample range for this output. To create a limited range output from a full
 * range input: Choose Limited range squeeze. For full range inputs, MediaConvert performs a linear offset to color
 * samples equally across all pixels and frames. Color samples in 10-bit outputs are limited to 64 through 940, and
 * 8-bit outputs are limited to 16 through 235. Note: For limited range inputs, values for color samples are passed
 * through to your output unchanged. MediaConvert does not limit the sample range. To correct pixels in your input that
 * are out of range or out of gamut: Choose Limited range clip. Use for broadcast applications. MediaConvert conforms
 * any pixels outside of the values that you specify under Minimum YUV and Maximum YUV to limited range bounds.
 * MediaConvert also corrects any YUV values that, when converted to RGB, would be outside the bounds you specify under
 * Minimum RGB tolerance and Maximum RGB tolerance. With either limited range conversion, MediaConvert writes the sample
 * range metadata in the output.
 */
final class SampleRangeConversion
{
    public const LIMITED_RANGE_CLIP = 'LIMITED_RANGE_CLIP';
    public const LIMITED_RANGE_SQUEEZE = 'LIMITED_RANGE_SQUEEZE';
    public const NONE = 'NONE';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::LIMITED_RANGE_CLIP => true,
            self::LIMITED_RANGE_SQUEEZE => true,
            self::NONE => true,
        ][$value]);
    }
}
