<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify how the value for bandwidth is determined for each video Representation in your output MPD manifest. We
 * recommend that you choose a MPD manifest bandwidth type that is compatible with your downstream player configuration.
 * Max: Use the same value that you specify for Max bitrate in the video output, in bits per second. Average: Use the
 * calculated average bitrate of the encoded video output, in bits per second.
 */
final class CmafMpdManifestBandwidthType
{
    public const AVERAGE = 'AVERAGE';
    public const MAX = 'MAX';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::AVERAGE => true,
            self::MAX => true,
        ][$value]);
    }
}
