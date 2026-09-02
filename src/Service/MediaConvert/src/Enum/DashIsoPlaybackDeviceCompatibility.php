<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * This setting can improve the compatibility of your output with video players on obsolete devices. It applies only to
 * DASH outputs with DRM encryption. Choose Unencrypted SEI only to correct problems with playback on older H.264
 * devices. Choose CENC v1 unencrypted headers to leave NAL unit headers and slice headers unencrypted for H.265
 * outputs, improving compatibility with strict HEVC decoders. Otherwise, keep the default setting CENC v1.
 */
final class DashIsoPlaybackDeviceCompatibility
{
    public const CENC_V1 = 'CENC_V1';
    public const CENC_V1_UNENCRYPTED_HEADERS = 'CENC_V1_UNENCRYPTED_HEADERS';
    public const UNENCRYPTED_SEI = 'UNENCRYPTED_SEI';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::CENC_V1 => true,
            self::CENC_V1_UNENCRYPTED_HEADERS => true,
            self::UNENCRYPTED_SEI => true,
        ][$value]);
    }
}
