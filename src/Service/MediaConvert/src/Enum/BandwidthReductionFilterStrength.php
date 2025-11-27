<?php

namespace AsyncAws\MediaConvert\Enum;

/**
 * Specify the strength of the Bandwidth reduction filter. For most workflows, we recommend that you choose Auto to
 * reduce the bandwidth of your output with little to no perceptual decrease in video quality. For high quality and high
 * bitrate outputs, choose Low. For the most bandwidth reduction, choose High. We recommend that you choose High for low
 * bitrate outputs. Note that High may incur a slight increase in the softness of your output.
 */
final class BandwidthReductionFilterStrength
{
    public const AUTO = 'AUTO';
    public const HIGH = 'HIGH';
    public const LOW = 'LOW';
    public const MEDIUM = 'MEDIUM';
    public const OFF = 'OFF';
    public const UNKNOWN_TO_SDK = 'UNKNOWN_TO_SDK';

    /**
     * @psalm-assert-if-true self::* $value
     */
    public static function exists(string $value): bool
    {
        return isset([
            self::AUTO => true,
            self::HIGH => true,
            self::LOW => true,
            self::MEDIUM => true,
            self::OFF => true,
        ][$value]);
    }
}
