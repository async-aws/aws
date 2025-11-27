<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * This setting can improve the compatibility of your output with video players on obsolete devices. It applies only to
 * DASH H.264 outputs with DRM encryption. Choose Unencrypted SEI only to correct problems with playback on older
 * devices. Otherwise, keep the default setting CENC v1. If you choose Unencrypted SEI, for that output, the service
 * will exclude the access unit delimiter and will leave the SEI NAL units unencrypted.
 */
final class DashIsoPlaybackDeviceCompatibility
{
    public const CENC_V1 = 'CENC_V1';
    public const UNENCRYPTED_SEI = 'UNENCRYPTED_SEI';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::CENC_V1 => true,
            self::UNENCRYPTED_SEI => true,
        ][$value]);
    }
}
