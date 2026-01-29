<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Choose the audio frame wrapping mode for PCM tracks in MXF outputs. AUTO (default): Uses codec-appropriate defaults -
 * BWF for H.264/AVC, AES3 for MPEG2/XDCAM. AES3: Use AES3 frame wrapping with SMPTE-compliant descriptors. This setting
 * only takes effect when the MXF profile is OP1a.
 */
final class MxfUncompressedAudioWrapping
{
    public const AES3 = 'AES3';
    public const AUTO = 'AUTO';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::AES3 => true,
            self::AUTO => true,
        ][$value]);
    }
}
