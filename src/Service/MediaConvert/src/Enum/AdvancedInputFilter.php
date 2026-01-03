<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Use to remove noise, blocking, blurriness, or ringing from your input as a pre-filter step before encoding. The
 * Advanced input filter removes more types of compression artifacts and is an improvement when compared to basic
 * Deblock and Denoise filters. To remove video compression artifacts from your input and improve the video quality:
 * Choose Enabled. Additionally, this filter can help increase the video quality of your output relative to its bitrate,
 * since noisy inputs are more complex and require more bits to encode. To help restore loss of detail after applying
 * the filter, you can optionally add texture or sharpening as an additional step. Jobs that use this feature incur
 * pro-tier pricing. To not apply advanced input filtering: Choose Disabled. Note that you can still apply basic
 * filtering with Deblock and Denoise.
 */
final class AdvancedInputFilter
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
