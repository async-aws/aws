<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Film grain synthesis replaces film grain present in your content with similar quality synthesized AV1 film grain. We
 * recommend that you choose Enabled to reduce the bandwidth of your QVBR quality level 5, 6, 7, or 8 outputs. For QVBR
 * quality level 9 or 10 outputs we recommend that you keep the default value, Disabled. When you include Film grain
 * synthesis, you cannot include the Noise reducer preprocessor.
 */
final class Av1FilmGrainSynthesis
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
